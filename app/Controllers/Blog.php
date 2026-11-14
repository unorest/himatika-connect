<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\CategoryModel;

class Blog extends BaseController
{
    protected $postModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->postModel     = new PostModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categoryId = $this->request->getGet('category') ? (int) $this->request->getGet('category') : null;
        $search     = $this->request->getGet('q');

        $builder = $this->postModel->select('hmt_posts.*, hmt_categories.name as category_name, hmt_categories.color as category_color, hmt_users.name as author_name')
                                   ->join('hmt_categories', 'hmt_categories.id = hmt_posts.category_id', 'left')
                                   ->join('hmt_users', 'hmt_users.id = hmt_posts.author_id', 'left')
                                   ->where('hmt_posts.status', 'published')
                                   ->where('hmt_posts.deleted_at', null);

        if (!empty($categoryId)) {
            $builder->where('hmt_posts.category_id', $categoryId);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('hmt_posts.title', $search)
                    ->orLike('hmt_posts.body', $search)
                    ->groupEnd();
        }

        $posts      = $builder->orderBy('hmt_posts.published_at', 'DESC')->findAll(12);
        $categories = $this->categoryModel->getByType('blog');

        $data = [
            'title'            => 'Warta & Berita Kegiatan — HIMATIKA Connect',
            'posts'            => $posts,
            'categories'       => $categories,
            'selectedCategory' => $categoryId,
            'searchQuery'      => $search,
        ];

        return view('blog/index', $data);
    }

    public function detail($slug)
    {
        $post = $this->postModel->select('hmt_posts.*, hmt_categories.name as category_name, hmt_categories.color as category_color, hmt_users.name as author_name, hmt_divisions.name as division_name')
                                ->join('hmt_categories', 'hmt_categories.id = hmt_posts.category_id', 'left')
                                ->join('hmt_users', 'hmt_users.id = hmt_posts.author_id', 'left')
                                ->join('hmt_divisions', 'hmt_divisions.id = hmt_users.division_id', 'left')
                                ->where('hmt_posts.slug', $slug)
                                ->where('hmt_posts.deleted_at', null)
                                ->first();

        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Artikel berita tidak ditemukan.');
        }

        // Increment Views
        $this->postModel->where('id', $post['id'])->increment('views', 1);

        // Rekomendasi Berita Lainnya
        $relatedPosts = $this->postModel->where('status', 'published')
                                        ->where('id !=', $post['id'])
                                        ->orderBy('published_at', 'DESC')
                                        ->findAll(3);

        $data = [
            'title'        => esc($post['title']) . ' — HIMATIKA News',
            'post'         => $post,
            'relatedPosts' => $relatedPosts,
        ];

        return view('blog/detail', $data);
    }
}
