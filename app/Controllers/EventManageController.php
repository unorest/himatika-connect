<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\CategoryModel;
use App\Models\DivisionModel;

class EventManageController extends BaseController
{
    protected $eventModel;
    protected $categoryModel;
    protected $divisionModel;

    public function __construct()
    {
        $this->eventModel    = new EventModel();
        $this->categoryModel = new CategoryModel();
        $this->divisionModel = new DivisionModel();
    }

    /**
     * Hanya Admin BPH & Kadiv yang dapat menambah, mengubah, atau menghapus event publik.
     */
    private function canManageEvents(): bool
    {
        return in_array(session()->get('user_role'), ['admin', 'kadiv'], true);
    }

    /**
     * Ubah format input HTML datetime-local (YYYY-MM-DDTHH:MM) menjadi DATETIME MySQL.
     */
    private function normalizeDateTime(?string $input): ?string
    {
        $clean = trim((string) $input);
        if ($clean === '') {
            return null;
        }
        $ts = strtotime(str_replace('T', ' ', $clean));
        return $ts ? date('Y-m-d H:i:s', $ts) : null;
    }

    public function index()
    {
        $status = $this->request->getGet('status') ?: 'all';
        $search = trim((string) ($this->request->getGet('q') ?? ''));
        $now    = date('Y-m-d H:i:s');

        $builder = $this->eventModel->select('hmt_events.*, hmt_categories.name as category_name, hmt_categories.color as category_color, hmt_divisions.name as division_name, creator.name as creator_name')
                                    ->join('hmt_categories', 'hmt_categories.id = hmt_events.category_id', 'left')
                                    ->join('hmt_divisions', 'hmt_divisions.id = hmt_events.division_id', 'left')
                                    ->join('hmt_users as creator', 'creator.id = hmt_events.created_by', 'left')
                                    ->where('hmt_events.deleted_at', null);

        if ($status === 'upcoming') {
            $builder->where('hmt_events.end_datetime >=', $now);
        } elseif ($status === 'past') {
            $builder->where('hmt_events.end_datetime <', $now);
        }

        if ($search !== '') {
            $builder->groupStart()
                    ->like('hmt_events.title', $search)
                    ->orLike('hmt_events.location', $search)
                    ->orLike('hmt_events.description', $search)
                    ->groupEnd();
        }

        $events = $builder->orderBy('hmt_events.start_datetime', 'DESC')->findAll();

        $allEvents = $this->eventModel->where('deleted_at', null)->findAll();
        $stats = [
            'total'    => count($allEvents),
            'upcoming' => count(array_filter($allEvents, fn ($e) => $e['end_datetime'] >= $now)),
            'public'   => count(array_filter($allEvents, fn ($e) => (int) $e['is_public'] === 1 && $e['end_datetime'] >= $now)),
        ];

        $categories = $this->categoryModel->getByType('event');
        $divisions  = $this->divisionModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        $data = [
            'title'           => 'Kelola Event & Workshop Publik — HIMATIKA Hub',
            'events'          => $events,
            'categories'      => $categories,
            'divisions'       => $divisions,
            'stats'           => $stats,
            'selectedStatus'  => $status,
            'searchQuery'     => $search,
            'canManageEvents' => $this->canManageEvents(),
        ];

        return view('dashboard/events/index', $data);
    }

    public function store()
    {
        if (!$this->canManageEvents()) {
            return redirect()->to(site_url('dashboard/events'))
                             ->with('error', 'Akses ditolak. Hanya Admin BPH atau Kepala Divisi yang dapat membuat event publik.');
        }

        $rules = [
            'title'          => 'required|min_length[3]|max_length[255]',
            'start_datetime' => 'required',
            'end_datetime'   => 'required',
            'location'       => 'permit_empty|max_length[255]',
            'max_participants' => 'permit_empty|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(site_url('dashboard/events'))
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $startDt = $this->normalizeDateTime($this->request->getPost('start_datetime'));
        $endDt   = $this->normalizeDateTime($this->request->getPost('end_datetime'));

        if (!$startDt || !$endDt) {
            return redirect()->to(site_url('dashboard/events'))
                             ->withInput()
                             ->with('error', 'Format tanggal mulai atau selesai tidak valid.');
        }

        if (strtotime($endDt) < strtotime($startDt)) {
            return redirect()->to(site_url('dashboard/events'))
                             ->withInput()
                             ->with('error', 'Waktu selesai event tidak boleh lebih awal dari waktu mulai.');
        }

        $title = trim((string) $this->request->getPost('title'));
        $slug  = url_title($title, '-', true);
        if ($this->eventModel->where('slug', $slug)->first()) {
            $slug .= '-' . time();
        }

        $divisionId = $this->request->getPost('division_id') ? (int) $this->request->getPost('division_id') : null;
        $categoryId = $this->request->getPost('category_id') ? (int) $this->request->getPost('category_id') : null;
        $maxPart    = trim((string) ($this->request->getPost('max_participants') ?? ''));

        $color = '#2563EB';
        if ($divisionId !== null) {
            $div = $this->divisionModel->find($divisionId);
            if ($div && !empty($div['color'])) {
                $color = $div['color'];
            }
        }

        $data = [
            'title'                 => $title,
            'slug'                  => $slug,
            'description'           => trim((string) ($this->request->getPost('description') ?? '')),
            'location'              => trim((string) ($this->request->getPost('location') ?? 'Kampus / Online')),
            'start_datetime'        => $startDt,
            'end_datetime'          => $endDt,
            'category_id'           => $categoryId,
            'division_id'           => $divisionId,
            'color'                 => $color,
            'is_public'             => $this->request->getPost('is_public') ? 1 : 0,
            'allow_registration'    => $this->request->getPost('allow_registration') ? 1 : 0,
            'max_participants'      => ($maxPart !== '' && (int) $maxPart > 0) ? (int) $maxPart : null,
            'registration_deadline' => $this->normalizeDateTime($this->request->getPost('registration_deadline')),
            'created_by'            => (int) session()->get('user_id'),
        ];

        $this->eventModel->insert($data);

        return redirect()->to(site_url('dashboard/events'))
                         ->with('success', 'Event publik "' . $title . '" berhasil ditambahkan dan siap tampil di beranda!');
    }

    public function update($id)
    {
        if (!$this->canManageEvents()) {
            return redirect()->to(site_url('dashboard/events'))
                             ->with('error', 'Akses ditolak. Hanya Admin BPH atau Kepala Divisi yang dapat mengubah event publik.');
        }

        $id    = (int) $id;
        $event = $this->eventModel->find($id);
        if (!$event) {
            return redirect()->to(site_url('dashboard/events'))->with('error', 'Event tidak ditemukan.');
        }

        $rules = [
            'title'            => 'required|min_length[3]|max_length[255]',
            'start_datetime'   => 'required',
            'end_datetime'     => 'required',
            'location'         => 'permit_empty|max_length[255]',
            'max_participants' => 'permit_empty|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(site_url('dashboard/events'))
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $startDt = $this->normalizeDateTime($this->request->getPost('start_datetime'));
        $endDt   = $this->normalizeDateTime($this->request->getPost('end_datetime'));

        if (!$startDt || !$endDt || strtotime($endDt) < strtotime($startDt)) {
            return redirect()->to(site_url('dashboard/events'))
                             ->withInput()
                             ->with('error', 'Rentang waktu mulai dan selesai event tidak valid.');
        }

        $divisionId = $this->request->getPost('division_id') ? (int) $this->request->getPost('division_id') : null;
        $categoryId = $this->request->getPost('category_id') ? (int) $this->request->getPost('category_id') : null;
        $maxPart    = trim((string) ($this->request->getPost('max_participants') ?? ''));

        $data = [
            'title'                 => trim((string) $this->request->getPost('title')),
            'description'           => trim((string) ($this->request->getPost('description') ?? '')),
            'location'              => trim((string) ($this->request->getPost('location') ?? 'Kampus / Online')),
            'start_datetime'        => $startDt,
            'end_datetime'          => $endDt,
            'category_id'           => $categoryId,
            'division_id'           => $divisionId,
            'is_public'             => $this->request->getPost('is_public') ? 1 : 0,
            'allow_registration'    => $this->request->getPost('allow_registration') ? 1 : 0,
            'max_participants'      => ($maxPart !== '' && (int) $maxPart > 0) ? (int) $maxPart : null,
            'registration_deadline' => $this->normalizeDateTime($this->request->getPost('registration_deadline')),
        ];

        $this->eventModel->update($id, $data);

        return redirect()->to(site_url('dashboard/events'))->with('success', 'Data event publik berhasil diperbarui!');
    }

    public function delete($id)
    {
        if (!$this->canManageEvents()) {
            return redirect()->to(site_url('dashboard/events'))
                             ->with('error', 'Akses ditolak. Hanya Admin BPH atau Kepala Divisi yang dapat menghapus event publik.');
        }

        $id    = (int) $id;
        $event = $this->eventModel->find($id);
        if (!$event) {
            return redirect()->to(site_url('dashboard/events'))->with('error', 'Event tidak ditemukan.');
        }

        $this->eventModel->delete($id);

        return redirect()->to(site_url('dashboard/events'))->with('success', 'Event publik berhasil dihapus.');
    }
}
