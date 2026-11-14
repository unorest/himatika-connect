<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table            = 'hmt_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'description', 'division_id', 'start_datetime', 'end_datetime',
        'location', 'color', 'is_recurring', 'recurrence_rule', 'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getSchedulesWithDetails(?int $divisionId = null, ?int $currentUserId = null)
    {
        $builder = $this->select('hmt_schedules.*, hmt_divisions.name as division_name, hmt_divisions.color as division_color, creator.name as creator_name')
                        ->join('hmt_divisions', 'hmt_divisions.id = hmt_schedules.division_id', 'left')
                        ->join('hmt_users as creator', 'creator.id = hmt_schedules.created_by', 'left')
                        ->where('hmt_schedules.deleted_at', null);

        if (!empty($divisionId)) {
            $builder->groupStart()
                    ->where('hmt_schedules.division_id', $divisionId)
                    ->orWhere('hmt_schedules.division_id', null) // Rapat pleno umum
                    ->groupEnd();
        }

        $schedules = $builder->orderBy('hmt_schedules.start_datetime', 'ASC')
                             ->findAll();

        if (empty($schedules)) {
            return [];
        }

        $scheduleIds = array_column($schedules, 'id');

        // Ambil semua RSVP attendees
        $attendees = $this->db->table('hmt_schedule_attendees')
                              ->select('hmt_schedule_attendees.*, hmt_users.name as user_name, hmt_users.avatar as user_avatar, hmt_users.role as user_role')
                              ->join('hmt_users', 'hmt_users.id = hmt_schedule_attendees.user_id', 'left')
                              ->whereIn('schedule_id', $scheduleIds)
                              ->get()
                              ->getResultArray();

        $attendeesBySchedule = [];
        $myRsvpBySchedule    = [];

        foreach ($attendees as $att) {
            $attendeesBySchedule[$att['schedule_id']][] = $att;
            if ($currentUserId && $att['user_id'] == $currentUserId) {
                $myRsvpBySchedule[$att['schedule_id']] = $att['rsvp'];
            }
        }

        foreach ($schedules as &$sch) {
            $sch['attendees']   = $attendeesBySchedule[$sch['id']] ?? [];
            $sch['my_rsvp']     = $myRsvpBySchedule[$sch['id']] ?? 'pending';

            // Hitung ringkasan rsvp
            $accepted = 0;
            $declined = 0;
            $pending  = 0;
            foreach ($sch['attendees'] as $a) {
                if ($a['rsvp'] === 'accepted') $accepted++;
                elseif ($a['rsvp'] === 'declined') $declined++;
                else $pending++;
            }
            $sch['rsvp_summary'] = [
                'accepted' => $accepted,
                'declined' => $declined,
                'pending'  => $pending,
                'total'    => count($sch['attendees']),
            ];
        }

        return $schedules;
    }
}
