<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table            = 'hmt_posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'slug', 'body', 'excerpt', 'thumbnail',
        'author_id', 'category_id', 'status', 'read_time', 'views', 'published_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getPublished(int $limit = 6)
    {
        return $this->select('hmt_posts.*, hmt_categories.name as category_name, hmt_categories.color as category_color, hmt_users.name as author_name')
                    ->join('hmt_categories', 'hmt_categories.id = hmt_posts.category_id', 'left')
                    ->join('hmt_users', 'hmt_users.id = hmt_posts.author_id', 'left')
                    ->where('hmt_posts.status', 'published')
                    ->orderBy('hmt_posts.published_at', 'DESC')
                    ->findAll($limit);
    }
}
