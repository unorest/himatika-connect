<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table            = 'hmt_events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'slug', 'description', 'location', 'start_datetime', 'end_datetime',
        'category_id', 'division_id', 'color', 'is_public', 'allow_registration',
        'max_participants', 'registration_deadline', 'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getUpcoming(int $limit = 4)
    {
        return $this->select('hmt_events.*, hmt_categories.name as category_name, hmt_divisions.name as division_name')
                    ->join('hmt_categories', 'hmt_categories.id = hmt_events.category_id', 'left')
                    ->join('hmt_divisions', 'hmt_divisions.id = hmt_events.division_id', 'left')
                    ->where('hmt_events.is_public', 1)
                    ->where('hmt_events.end_datetime >=', date('Y-m-d H:i:s'))
                    ->orderBy('hmt_events.start_datetime', 'ASC')
                    ->findAll($limit);
    }
}
