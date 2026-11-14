<?php

namespace App\Models;

use CodeIgniter\Model;

class ForumAnswerModel extends Model
{
    protected $table            = 'hmt_forum_answers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'question_id', 'body', 'answered_by', 'is_official'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
