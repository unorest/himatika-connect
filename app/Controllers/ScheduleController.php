<?php

namespace App\Controllers;

use App\Models\ScheduleModel;
use App\Models\ScheduleAttendeeModel;
use App\Models\DivisionModel;
use App\Models\UserModel;

class ScheduleController extends BaseController
{
    protected $scheduleModel;
    protected $attendeeModel;
    protected $divisionModel;
    protected $userModel;

    public function __construct()
    {
        $this->scheduleModel = new ScheduleModel();
        $this->attendeeModel = new ScheduleAttendeeModel();
        $this->divisionModel = new DivisionModel();
        $this->userModel     = new UserModel();
    }

    /**
     * Admin & Kadiv boleh menghapus semua agenda; Staff hanya agenda miliknya sendiri.
     */
    private function canManageSchedule(array $schedule): bool
    {
        $role   = session()->get('user_role');
        $userId = (int) session()->get('user_id');

        if (in_array($role, ['admin', 'kadiv'], true)) {
            return true;
        }

        return (int) ($schedule['created_by'] ?? 0) === $userId;
    }

    public function index()
    {
        $userId         = session()->get('user_id');
        $userDivisionId = session()->get('user_division_id');
        $userRole       = session()->get('user_role');

        $divisionFilter = $this->request->getGet('division') !== null && $this->request->getGet('division') !== ''
            ? (int) $this->request->getGet('division')
            : null;

        $schedules = $this->scheduleModel->getSchedulesWithDetails($divisionFilter, $userId);
        $divisions = $this->divisionModel->where('is_active', 1)->findAll();

        // Hitung statistik ringkas jadwal
        $now = date('Y-m-d H:i:s');
        $stats = [
            'total_upcoming' => 0,
            'my_accepted'    => 0,
            'my_pending'     => 0,
        ];

        foreach ($schedules as $s) {
            if ($s['start_datetime'] >= $now) {
                $stats['total_upcoming']++;
            }
            if ($s['my_rsvp'] === 'accepted') {
                $stats['my_accepted']++;
            } elseif ($s['my_rsvp'] === 'pending') {
                $stats['my_pending']++;
            }
        }

        // Siapkan Event Markers untuk Kalender Mini Bulanan
        $calendarEvents = [];
        foreach ($schedules as $s) {
            $date = date('Y-m-d', strtotime($s['start_datetime']));
            $calendarEvents[$date][] = [
                'title' => $s['title'],
                'color' => $s['color'] ?? $s['division_color'] ?? '#3b82f6',
                'time'  => date('H:i', strtotime($s['start_datetime'])),
            ];
        }

        $data = [
            'title'            => 'Agenda & Rapat Internal — HIMATIKA Hub',
            'schedules'        => $schedules,
            'divisions'        => $divisions,
            'selectedDivision' => $divisionFilter,
            'stats'            => $stats,
            'calendarEvents'   => $calendarEvents,
        ];

        return view('dashboard/schedules/index', $data);
    }

    public function store()
    {
        $rules = [
            'title'          => 'required|min_length[3]|max_length[255]',
            'start_datetime' => 'required',
            'end_datetime'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId     = session()->get('user_id');
        $divisionId = $this->request->getPost('division_id') ? (int) $this->request->getPost('division_id') : null;

        // Ambil warna divisi jika tidak dicustom
        $color = $this->request->getPost('color');
        if (empty($color) && !empty($divisionId)) {
            $div = $this->divisionModel->find($divisionId);
            $color = $div['color'] ?? '#3b82f6';
        } elseif (empty($color)) {
            $color = '#1D4ED8'; // Pleno / BPH
        }

        $scheduleData = [
            'title'          => trim($this->request->getPost('title')),
            'description'    => trim($this->request->getPost('description') ?? ''),
            'division_id'    => $divisionId,
            'start_datetime' => $this->request->getPost('start_datetime'),
            'end_datetime'   => $this->request->getPost('end_datetime'),
            'location'       => trim($this->request->getPost('location') ?? 'Sekretariat HIMATIKA / Hybrid'),
            'color'          => $color,
            'is_recurring'   => $this->request->getPost('is_recurring') ? 1 : 0,
            'created_by'     => $userId,
        ];

        $this->scheduleModel->insert($scheduleData);
        $scheduleId = $this->scheduleModel->getInsertID();

        // Undang peserta rapat secara otomatis
        $usersQuery = $this->userModel->where('is_active', 1);
        if (!empty($divisionId)) {
            $usersQuery->where('division_id', $divisionId);
        }
        $targetUsers = $usersQuery->findAll();

        $attendeesBatch = [];
        $now = date('Y-m-d H:i:s');
        foreach ($targetUsers as $u) {
            $attendeesBatch[] = [
                'schedule_id' => $scheduleId,
                'user_id'     => $u['id'],
                'rsvp'        => ($u['id'] == $userId) ? 'accepted' : 'pending',
                'created_at'  => $now,
            ];
        }

        if (!empty($attendeesBatch)) {
            $this->attendeeModel->insertBatch($attendeesBatch);
        }

        return redirect()->to(site_url('dashboard/schedules'))->with('success', 'Agenda rapat/kegiatan internal berhasil dijadwalkan!');
    }

    public function rsvp()
    {
        $scheduleId = (int) $this->request->getPost('schedule_id');
        $rsvp       = $this->request->getPost('rsvp'); // 'accepted' or 'declined'
        $userId     = session()->get('user_id');

        if (!in_array($rsvp, ['accepted', 'declined'], true)) {
            return redirect()->back()->with('error', 'Status konfirmasi tidak valid.');
        }

        $existing = $this->attendeeModel->where('schedule_id', $scheduleId)
                                         ->where('user_id', $userId)
                                         ->first();

        if ($existing) {
            $this->attendeeModel->update($existing['id'], ['rsvp' => $rsvp]);
        } else {
            $this->attendeeModel->insert([
                'schedule_id' => $scheduleId,
                'user_id'     => $userId,
                'rsvp'        => $rsvp,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $msg = ($rsvp === 'accepted') ? 'Konfirmasi HADIR berhasil disimpan!' : 'Konfirmasi TIDAK HADIR telah dicatat.';
        return redirect()->back()->with('success', $msg);
    }

    public function delete($id)
    {
        $id       = (int) $id;
        $schedule = $this->scheduleModel->find($id);

        if (!$schedule) {
            return redirect()->to(site_url('dashboard/schedules'))->with('error', 'Jadwal agenda tidak ditemukan.');
        }

        if (!$this->canManageSchedule($schedule)) {
            return redirect()->to(site_url('dashboard/schedules'))->with('error', 'Akses ditolak. Anda hanya dapat menghapus agenda milik Anda sendiri.');
        }

        $this->scheduleModel->delete($id);

        return redirect()->to(site_url('dashboard/schedules'))->with('success', 'Jadwal agenda berhasil dibatalkan/dihapus.');
    }
}
