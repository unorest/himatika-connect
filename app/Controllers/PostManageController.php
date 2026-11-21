<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\CategoryModel;
use App\Libraries\HtmlSanitizer;

class PostManageController extends BaseController
{
    protected $postModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->postModel     = new PostModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Admin & Kadiv boleh mengelola semua berita; Staff hanya berita miliknya sendiri.
     */
    private function canManagePost(array $post): bool
    {
        $role   = session()->get('user_role');
        $userId = (int) session()->get('user_id');

        if (in_array($role, ['admin', 'kadiv'], true)) {
            return true;
        }

        return (int) ($post['author_id'] ?? 0) === $userId;
    }

    /**
     * Aturan validasi berita dengan pesan bahasa Indonesia yang jelas.
     */
    private function postValidationRules(): array
    {
        return [
            'title' => [
                'label'  => 'Judul Berita',
                'rules'  => 'required|min_length[5]|max_length[255]',
                'errors' => [
                    'required'   => 'Judul berita wajib diisi.',
                    'min_length' => 'Judul berita minimal 5 karakter.',
                    'max_length' => 'Judul berita maksimal 255 karakter.',
                ],
            ],
            'body' => [
                'label'  => 'Isi Berita',
                'rules'  => 'required|min_length[20]',
                'errors' => [
                    'required'   => 'Isi konten berita wajib diisi.',
                    'min_length' => 'Isi konten berita minimal 20 karakter.',
                ],
            ],
            'category_id' => [
                'label'  => 'Kategori Berita',
                'rules'  => 'required|is_natural_no_zero',
                'errors' => [
                    'required'           => 'Kategori berita wajib dipilih.',
                    'is_natural_no_zero' => 'Kategori berita tidak valid.',
                ],
            ],
            'status' => [
                'label'  => 'Status Publikasi',
                'rules'  => 'required|in_list[draft,published,archived]',
                'errors' => [
                    'required' => 'Status publikasi wajib dipilih.',
                    'in_list'  => 'Status publikasi tidak valid.',
                ],
            ],
        ];
    }

    public function index()
    {
        $status     = $this->request->getGet('status');
        $categoryId = $this->request->getGet('category') ? (int) $this->request->getGet('category') : null;
        $search     = $this->request->getGet('q');

        $builder = $this->postModel->select('hmt_posts.*, hmt_categories.name as category_name, hmt_categories.color as category_color, hmt_users.name as author_name')
                                   ->join('hmt_categories', 'hmt_categories.id = hmt_posts.category_id', 'left')
                                   ->join('hmt_users', 'hmt_users.id = hmt_posts.author_id', 'left')
                                   ->where('hmt_posts.deleted_at', null);

        if (!empty($status)) {
            $builder->where('hmt_posts.status', $status);
        }

        if (!empty($categoryId)) {
            $builder->where('hmt_posts.category_id', $categoryId);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('hmt_posts.title', $search)
                    ->orLike('hmt_posts.excerpt', $search)
                    ->groupEnd();
        }

        $posts      = $builder->orderBy('hmt_posts.created_at', 'DESC')->findAll();
        $categories = $this->categoryModel->getByType('blog');

        $data = [
            'title'            => 'Kelola Berita & Artikel — HIMATIKA Hub',
            'posts'            => $posts,
            'categories'       => $categories,
            'selectedStatus'   => $status,
            'selectedCategory' => $categoryId,
            'searchQuery'      => $search,
        ];

        return view('dashboard/posts/index', $data);
    }

    public function create()
    {
        $categories = $this->categoryModel->getByType('blog');

        $data = [
            'title'      => 'Tulis Berita / Warta Baru — HIMATIKA Hub',
            'categories' => $categories,
            'post'       => null,
            'action'     => site_url('dashboard/posts/store'),
        ];

        return view('dashboard/posts/form', $data);
    }

    public function store()
    {
        if (!$this->validate($this->postValidationRules())) {
            return redirect()->to(site_url('dashboard/posts/create'))
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $title = trim((string) $this->request->getPost('title'));
        $slug  = url_title($title, '-', true);

        // Pastikan slug unik
        $existing = $this->postModel->where('slug', $slug)->first();
        if ($existing) {
            $slug .= '-' . time();
        }

        $status = $this->request->getPost('status');
        $now    = date('Y-m-d H:i:s');

        // Sanitasi HTML badan berita untuk mencegah Stored XSS
        $body             = HtmlSanitizer::clean((string) $this->request->getPost('body'));
        $wordCount        = str_word_count(strip_tags($body));
        $estimatedMinutes = max(1, (int) ceil($wordCount / 200));

        $data = [
            'title'        => $title,
            'slug'         => $slug,
            'body'         => $body,
            'excerpt'      => trim((string) ($this->request->getPost('excerpt') ?? '')),
            'thumbnail'    => trim((string) ($this->request->getPost('thumbnail') ?? '')),
            'author_id'    => session()->get('user_id'),
            'category_id'  => (int) $this->request->getPost('category_id'),
            'status'       => $status,
            'read_time'    => $this->request->getPost('read_time') ? (int) $this->request->getPost('read_time') : $estimatedMinutes,
            'published_at' => ($status === 'published') ? $now : null,
        ];

        $this->postModel->insert($data);

        return redirect()->to(site_url('dashboard/posts'))->with('success', 'Berita berhasil ' . ($status === 'published' ? 'diterbitkan!' : 'disimpan sebagai draf!'));
    }

    public function edit($id)
    {
        $id   = (int) $id;
        $post = $this->postModel->find($id);

        if (!$post) {
            return redirect()->to(site_url('dashboard/posts'))->with('error', 'Berita tidak ditemukan.');
        }

        if (!$this->canManagePost($post)) {
            return redirect()->to(site_url('dashboard/posts'))->with('error', 'Akses ditolak. Anda hanya dapat mengubah berita milik Anda sendiri.');
        }

        $categories = $this->categoryModel->getByType('blog');

        $data = [
            'title'      => 'Edit Berita: ' . esc($post['title']),
            'categories' => $categories,
            'post'       => $post,
            'action'     => site_url('dashboard/posts/update/' . $id),
        ];

        return view('dashboard/posts/form', $data);
    }

    public function update($id)
    {
        $id   = (int) $id;
        $post = $this->postModel->find($id);

        if (!$post) {
            return redirect()->to(site_url('dashboard/posts'))->with('error', 'Berita tidak ditemukan.');
        }

        if (!$this->canManagePost($post)) {
            return redirect()->to(site_url('dashboard/posts'))->with('error', 'Akses ditolak. Anda hanya dapat mengubah berita milik Anda sendiri.');
        }

        if (!$this->validate($this->postValidationRules())) {
            return redirect()->to(site_url('dashboard/posts/edit/' . $id))
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $status      = $this->request->getPost('status');
        $publishedAt = $post['published_at'];
        if ($status === 'published' && empty($publishedAt)) {
            $publishedAt = date('Y-m-d H:i:s');
        }

        $data = [
            'title'        => trim((string) $this->request->getPost('title')),
            'body'         => HtmlSanitizer::clean((string) $this->request->getPost('body')),
            'excerpt'      => trim((string) ($this->request->getPost('excerpt') ?? '')),
            'thumbnail'    => trim((string) ($this->request->getPost('thumbnail') ?? '')),
            'category_id'  => (int) $this->request->getPost('category_id'),
            'status'       => $status,
            'read_time'    => (int) $this->request->getPost('read_time'),
            'published_at' => $publishedAt,
        ];

        $this->postModel->update($id, $data);

        return redirect()->to(site_url('dashboard/posts'))->with('success', 'Perubahan berita berhasil disimpan!');
    }

    public function delete($id)
    {
        $id   = (int) $id;
        $post = $this->postModel->find($id);

        if (!$post) {
            return redirect()->to(site_url('dashboard/posts'))->with('error', 'Berita tidak ditemukan.');
        }

        if (!$this->canManagePost($post)) {
            return redirect()->to(site_url('dashboard/posts'))->with('error', 'Akses ditolak. Anda hanya dapat menghapus berita milik Anda sendiri.');
        }

        $this->postModel->delete($id);

        return redirect()->to(site_url('dashboard/posts'))->with('success', 'Berita berhasil dihapus.');
    }
}
