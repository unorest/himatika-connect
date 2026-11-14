<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkProgramModel extends Model
{
    protected $table            = 'hmt_work_programs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'description', 'division_id', 'period_year', 'period_term',
        'start_date', 'end_date', 'status', 'progress', 'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
