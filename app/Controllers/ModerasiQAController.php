<?php

namespace App\Controllers;

use App\Models\ForumQuestionModel;
use App\Models\ForumAnswerModel;
use App\Models\CategoryModel;

class ModerasiQAController extends BaseController
{
    protected $questionModel;
    protected $answerModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->questionModel = new ForumQuestionModel();
        $this->answerModel   = new ForumAnswerModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $status     = $this->request->getGet('status') ?? 'pending';
        $categoryId = $this->request->getGet('category') ? (int) $this->request->getGet('category') : null;
        $search     = $this->request->getGet('q');

        $db = \Config\Database::connect();

        // Hitung Counter Status
        $counts = [
            'pending'  => $db->table('hmt_forum_questions')->where('status', 'pending')->where('deleted_at', null)->countAllResults(),
            'answered' => $db->table('hmt_forum_questions')->where('status', 'answered')->where('deleted_at', null)->countAllResults(),
            'rejected' => $db->table('hmt_forum_questions')->where('status', 'rejected')->where('deleted_at', null)->countAllResults(),
        ];

        // Query Pertanyaan dengan Join
        $builder = $db->table('hmt_forum_questions')
                      ->select('hmt_forum_questions.*, hmt_categories.name as category_name, hmt_categories.color as category_color, moderator.name as moderator_name')
                      ->join('hmt_categories', 'hmt_categories.id = hmt_forum_questions.category_id', 'left')
                      ->join('hmt_users as moderator', 'moderator.id = hmt_forum_questions.moderated_by', 'left')
                      ->where('hmt_forum_questions.deleted_at', null);

        if (!empty($status) && in_array($status, ['pending', 'answered', 'rejected'], true)) {
            $builder->where('hmt_forum_questions.status', $status);
        }

        if (!empty($categoryId)) {
            $builder->where('hmt_forum_questions.category_id', $categoryId);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('hmt_forum_questions.title', $search)
                    ->orLike('hmt_forum_questions.body', $search)
                    ->orLike('hmt_forum_questions.author_name', $search)
                    ->groupEnd();
        }

        $questions = $builder->orderBy('hmt_forum_questions.is_pinned', 'DESC')
                             ->orderBy('hmt_forum_questions.created_at', 'DESC')
                             ->get()
                             ->getResultArray();

        $categories = $this->categoryModel->getByType('forum');

        $waSettings      = \App\Libraries\WhatsAppService::getSettings();
        $whatsappEnabled = $waSettings['enabled'] && !empty($waSettings['token']);
        $whatsappTarget  = $waSettings['target'];

        // Jangan pernah kirim token API mentah ke view HTML
        $safeWaSettings = [
            'enabled'  => $waSettings['enabled'],
            'target'   => $waSettings['target'],
            'provider' => $waSettings['provider'],
            'hasToken' => !empty($waSettings['token']),
        ];

        $data = [
            'title'            => 'Moderasi Aspirasi & Q&A — HIMATIKA Hub',
            'questions'        => $questions,
            'counts'           => $counts,
            'activeStatus'     => $status,
            'categories'       => $categories,
            'selectedCategory' => $categoryId,
            'searchQuery'      => $search,
            'whatsappEnabled'  => $whatsappEnabled,
            'whatsappTarget'   => $whatsappTarget,
            'waSettings'       => $safeWaSettings,
            'canManageWa'      => session()->get('user_role') === 'admin',
            'canModerateAdmin' => in_array(session()->get('user_role'), ['admin', 'kadiv'], true),
        ];

        return view('dashboard/moderasi_qa/index', $data);
    }

    /**
     * Simpan Pengaturan WhatsApp Langsung dari Dashboard (Khusus Admin BPH)
     */
    public function saveWhatsAppSettings()
    {
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to(site_url('dashboard/moderasi-qa'))
                             ->with('error', 'Akses ditolak. Hanya Admin BPH yang dapat mengubah pengaturan WhatsApp Gateway.');
        }

        $existing = \App\Libraries\WhatsAppService::getSettings();
        $enabled  = $this->request->getPost('whatsapp_enabled') === '1';
        $newToken = trim((string) ($this->request->getPost('whatsapp_token') ?? ''));
        $token    = $newToken !== '' ? $newToken : $existing['token'];
        $target   = trim((string) ($this->request->getPost('whatsapp_target') ?? ''));

        \App\Libraries\WhatsAppService::saveSettings($enabled, $token, $target);

        return redirect()->to(site_url('dashboard/moderasi-qa'))->with('success', 'Pengaturan notifikasi WhatsApp berhasil disimpan!');
    }

    /**
     * Uji Kirim Notifikasi WhatsApp dari Dashboard (Khusus Admin BPH)
     */
    public function testWhatsApp()
    {
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to(site_url('dashboard/moderasi-qa'))
                             ->with('error', 'Akses ditolak. Hanya Admin BPH yang dapat menjalankan uji kirim WhatsApp.');
        }

        $waSettings = \App\Libraries\WhatsAppService::getSettings();
        $target     = trim((string) ($this->request->getPost('target') ?: $waSettings['target']));
        $token      = trim((string) ($this->request->getPost('token') ?: $waSettings['token']));

        if (empty($token)) {
            return redirect()->to(site_url('dashboard/moderasi-qa'))->with('error', 'Token WhatsApp masih kosong. Masukkan Token API Fonnte Anda terlebih dahulu.');
        }

        if (empty($target)) {
            return redirect()->to(site_url('dashboard/moderasi-qa'))->with('error', 'Target nomor WhatsApp belum diisi.');
        }

        $res = \App\Libraries\WhatsAppService::testMessage($target, $token);

        if ($res['status'] === 'success') {
            $detail = !empty($res['detail']) ? ' (' . $res['detail'] . ')' : '';
            return redirect()->to(site_url('dashboard/moderasi-qa'))->with('success', 'Pesan uji coba WhatsApp berhasil dikirim ke ' . esc($target) . $detail . '!');
        }

        return redirect()->to(site_url('dashboard/moderasi-qa'))->with('error', 'Gagal mengirim pesan WhatsApp: ' . esc($res['message'] ?? 'Periksa token atau target Anda.'));
    }

    public function detail($id)
    {
        $id       = (int) $id;
        $question = $this->questionModel->getDetailWithAnswers($id);

        if (!$question) {
            return redirect()->to(site_url('dashboard/moderasi-qa'))->with('error', 'Pertanyaan tidak ditemukan atau sudah dihapus.');
        }

        $data = [
            'title'    => 'Tinjau Aspirasi: ' . esc($question['title']),
            'question' => $question,
        ];

        return view('dashboard/moderasi_qa/detail', $data);
    }

    public function answer()
    {
        $questionId = (int) $this->request->getPost('question_id');
        $body       = trim((string) ($this->request->getPost('body') ?? ''));
        $status     = $this->request->getPost('status') ?? 'answered';
        $isPinned   = $this->request->getPost('is_pinned') ? 1 : 0;

        if (!in_array($status, ['answered', 'rejected'], true)) {
            return redirect()->back()->with('error', 'Status moderasi tidak valid.');
        }

        if (empty($body) && $status === 'answered') {
            return redirect()->back()->withInput()->with('error', 'Tanggapan resmi tidak boleh kosong.');
        }

        $question = $this->questionModel->find($questionId);
        if (!$question) {
            return redirect()->to(site_url('dashboard/moderasi-qa'))->with('error', 'Pertanyaan tidak ditemukan.');
        }

        // Hanya admin & kadiv yang dapat mengubah status pin
        if (!in_array(session()->get('user_role'), ['admin', 'kadiv'], true)) {
            $isPinned = (int) ($question['is_pinned'] ?? 0);
        }

        $userId = session()->get('user_id');
        $now    = date('Y-m-d H:i:s');

        // Jika dijawab, simpan ke tabel hmt_forum_answers
        if (!empty($body)) {
            $this->answerModel->insert([
                'question_id' => $questionId,
                'answered_by' => $userId,
                'body'        => $body,
                'is_official' => 1,
            ]);
        }

        // Update status pertanyaan
        $this->questionModel->update($questionId, [
            'status'       => $status,
            'is_pinned'    => $isPinned,
            'moderated_by' => $userId,
            'moderated_at' => $now,
        ]);

        $message = ($status === 'answered')
            ? 'Tanggapan resmi berhasil dipublikasikan ke portal publik!'
            : 'Pertanyaan telah ditandai sebagai Ditolak/Spam.';

        return redirect()->to(site_url('dashboard/moderasi-qa'))->with('success', $message);
    }

    public function togglePin($id)
    {
        if (!in_array(session()->get('user_role'), ['admin', 'kadiv'], true)) {
            return redirect()->to(site_url('dashboard/moderasi-qa'))
                             ->with('error', 'Akses ditolak. Hanya Admin BPH atau Kadiv yang dapat menyematkan aspirasi.');
        }

        $id       = (int) $id;
        $question = $this->questionModel->find($id);

        if (!$question) {
            return redirect()->to(site_url('dashboard/moderasi-qa'))->with('error', 'Pertanyaan tidak ditemukan.');
        }

        $newPin = $question['is_pinned'] ? 0 : 1;
        $this->questionModel->update($id, ['is_pinned' => $newPin]);

        $text = $newPin ? 'Pertanyaan berhasil disematkan di paling atas portal.' : 'Semat pertanyaan dilepas.';
        return redirect()->to(site_url('dashboard/moderasi-qa'))->with('success', $text);
    }

    public function delete($id)
    {
        if (!in_array(session()->get('user_role'), ['admin', 'kadiv'], true)) {
            return redirect()->to(site_url('dashboard/moderasi-qa'))
                             ->with('error', 'Akses ditolak. Hanya Admin BPH atau Kadiv yang dapat menghapus aspirasi.');
        }

        $id       = (int) $id;
        $question = $this->questionModel->find($id);

        if (!$question) {
            return redirect()->to(site_url('dashboard/moderasi-qa'))->with('error', 'Pertanyaan tidak ditemukan.');
        }

        $this->questionModel->delete($id);

        return redirect()->to(site_url('dashboard/moderasi-qa'))->with('success', 'Aspirasi / pertanyaan berhasil dihapus.');
    }
}
