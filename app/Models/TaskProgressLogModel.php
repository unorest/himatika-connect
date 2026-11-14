<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskProgressLogModel extends Model
{
    protected $table            = 'hmt_task_progress_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'task_id', 'user_id', 'body', 'status_snapshot', 'attachment', 'logged_date'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
