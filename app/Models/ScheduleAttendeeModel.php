<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleAttendeeModel extends Model
{
    protected $table            = 'hmt_schedule_attendees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'schedule_id', 'user_id', 'rsvp'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
