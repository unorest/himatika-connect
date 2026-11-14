<?php

namespace App\Controllers;

use App\Models\ForumQuestionModel;
use App\Models\CategoryModel;

class Forum extends BaseController
{
    protected $forumModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->forumModel    = new ForumQuestionModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categoryId = $this->request->getGet('category') ? (int) $this->request->getGet('category') : null;
        $status     = $this->request->getGet('status');
        $search     = $this->request->getGet('q');

        $questions  = $this->forumModel->getFilteredQuestions($categoryId, $search, $status, 20, 0);
        $categories = $this->categoryModel->getByType('forum');

        $data = [
            'title'              => 'Forum Aspirasi & Q&A Terkurasi — HIMATIKA Connect',
            'questions'          => $questions,
            'categories'         => $categories,
            'selectedCategory'   => $categoryId,
            'selectedStatus'     => $status,
            'searchQuery'        => $search,
        ];

        return view('forum/index', $data);
    }

    public function detail($id)
    {
        $question = $this->forumModel->getDetailWithAnswers((int) $id);

        if (!$question) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pertanyaan tidak ditemukan atau sudah dihapus.');
        }

        $data = [
            'title'    => esc($question['title']) . ' — Forum HIMATIKA',
            'question' => $question,
        ];

        return view('forum/detail', $data);
    }

    public function store()
    {
        // Honeypot check (field tersembunyi yang tidak boleh diisi manusia)
        if (!empty($this->request->getPost('website_url'))) {
            return redirect()->back()->with('error', 'Spam detected.');
        }

        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'body'  => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isAnonymous = $this->request->getPost('is_anonymous') ? 1 : 0;
        $authorName  = trim($this->request->getPost('author_name') ?? '');
        if ($isAnonymous || empty($authorName)) {
            $authorName = 'Mahasiswa Anonim';
            $isAnonymous = 1;
        }

        $ipAddress = $this->request->getIPAddress();

        $data = [
            'title'        => $this->request->getPost('title'),
            'body'         => $this->request->getPost('body'),
            'author_name'  => $authorName,
            'author_nim'   => $isAnonymous ? null : trim($this->request->getPost('author_nim') ?? ''),
            'author_email' => $isAnonymous ? null : trim($this->request->getPost('author_email') ?? ''),
            'is_anonymous' => $isAnonymous,
            'category_id'  => $this->request->getPost('category_id') ? (int) $this->request->getPost('category_id') : null,
            'status'       => 'pending',
            'upvotes'      => 1,
            'ip_address'   => $ipAddress,
        ];

        $this->forumModel->insert($data);

        return redirect()->to(site_url('forum'))->with('success', 'Aspirasi / pertanyaanmu berhasil dikirim! Menunggu kurasi & verifikasi dari divisi terkait.');
    }

    public function upvote($id)
    {
        $id = (int) $id;
        $ip = $this->request->getIPAddress();
        $db = \Config\Database::connect();

        // Cek apakah IP ini sudah upvote
        $alreadyUpvoted = $db->table('hmt_forum_upvotes')
                            ->where('question_id', $id)
                            ->where('ip_address', $ip)
                            ->countAllResults();

        if ($alreadyUpvoted > 0) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Kamu sudah memberikan upvote pada aspirasi ini.']);
            }
            return redirect()->back()->with('warning', 'Kamu sudah memberikan upvote pada aspirasi ini.');
        }

        // Insert ke upvotes
        $db->table('hmt_forum_upvotes')->insert([
            'question_id' => $id,
            'ip_address'  => $ip,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        // Increment upvotes count
        $db->table('hmt_forum_questions')->where('id', $id)->increment('upvotes', 1);

        $newCount = $db->table('hmt_forum_questions')->select('upvotes')->where('id', $id)->get()->getRowArray()['upvotes'] ?? 0;

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'success', 'upvotes' => $newCount]);
        }

        return redirect()->back()->with('success', 'Terima kasih telah memberikan upvote!');
    }
}
