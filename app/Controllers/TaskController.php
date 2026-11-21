<?php

namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\TaskProgressLogModel;
use App\Models\WorkProgramModel;
use App\Models\UserModel;

class TaskController extends BaseController
{
    protected $taskModel;
    protected $logModel;
    protected $programModel;
    protected $userModel;

    public function __construct()
    {
        $this->taskModel    = new TaskModel();
        $this->logModel     = new TaskProgressLogModel();
        $this->programModel = new WorkProgramModel();
        $this->userModel    = new UserModel();
    }

    /**
     * Ambil ID divisi user dari session secara konsisten.
     */
    private function getUserDivisionId(): ?int
    {
        $divId = session()->get('user_division_id') ?? session()->get('division_id');
        return !empty($divId) ? (int) $divId : null;
    }

    /**
     * Validasi akses lintas divisi: Admin bebas; Kadiv/Staff hanya program kerja divisinya sendiri.
     */
    private function canAccessProgram(?array $program): bool
    {
        if (!$program) {
            return false;
        }

        if (session()->get('user_role') === 'admin') {
            return true;
        }

        $userDivisionId = $this->getUserDivisionId();
        if ($userDivisionId === null) {
            // Pengurus BPH tanpa divisi spesifik
            return true;
        }

        return (int) ($program['division_id'] ?? 0) === $userDivisionId;
    }

    /**
     * Pastikan URL lampiran menggunakan skema http/https yang aman (mencegah javascript: XSS).
     */
    private function sanitizeHttpUrl(?string $url): ?string
    {
        $clean = trim((string) $url);
        if ($clean === '') {
            return null;
        }

        $scheme = strtolower((string) parse_url($clean, PHP_URL_SCHEME));
        if (!in_array($scheme, ['http', 'https'], true) || !filter_var($clean, FILTER_VALIDATE_URL)) {
            return null;
        }

        return $clean;
    }

    public function index()
    {
        $userDivisionId = $this->getUserDivisionId();
        $userRole       = session()->get('user_role');

        $programId = $this->request->getGet('program_id') ? (int) $this->request->getGet('program_id') : null;

        // Ambil daftar program kerja yang boleh diakses user
        $programsQuery = $this->programModel->select('hmt_work_programs.*, hmt_divisions.name as division_name')
                                            ->join('hmt_divisions', 'hmt_divisions.id = hmt_work_programs.division_id', 'left')
                                            ->where('hmt_work_programs.deleted_at', null);

        if ($userRole !== 'admin' && $userDivisionId !== null) {
            $programsQuery->where('hmt_work_programs.division_id', $userDivisionId);
        }
        $programs = $programsQuery->findAll();

        // Validasi program_id yang diminta agar tidak bisa mengintip proker divisi lain
        $allowedProgramIds = array_map('intval', array_column($programs, 'id'));
        if ($programId !== null && !in_array($programId, $allowedProgramIds, true)) {
            $programId = null;
        }

        if (empty($programId) && !empty($programs)) {
            $programId = (int) $programs[0]['id'];
        }

        // Ambil kanban tasks
        $kanban = $this->taskModel->getKanbanTasks($programId);

        // Ambil data program yang sedang aktif
        $activeProgram = null;
        if (!empty($programId)) {
            $activeProgram = $this->programModel->find($programId);
        }

        // Ambil daftar pengurus divisi untuk opsi penugasan (PIC)
        $membersQuery = $this->userModel->where('is_active', 1);
        if ($userRole !== 'admin' && $userDivisionId !== null) {
            $membersQuery->where('division_id', $userDivisionId);
        }
        $members = $membersQuery->orderBy('name', 'ASC')->findAll();

        // Ambil daftar divisi untuk opsi pembuatan proker (khusus Admin)
        $divisions = (new \App\Models\DivisionModel())->where('deleted_at', null)->orderBy('name', 'ASC')->findAll();

        $data = [
            'title'             => 'Task Tracker Kanban — HIMATIKA Hub',
            'kanban'            => $kanban,
            'programs'          => $programs,
            'selectedProgramId' => $programId,
            'activeProgram'     => $activeProgram,
            'members'           => $members,
            'divisions'         => $divisions,
            'userRole'          => $userRole,
            'userDivisionId'    => $userDivisionId,
        ];

        return view('dashboard/tasks/index', $data);
    }

    /**
     * Tambah Program Kerja (Proker) Baru — Khusus Admin BPH & Kadiv
     */
    public function storeProgram()
    {
        $userRole       = session()->get('user_role');
        $userDivisionId = $this->getUserDivisionId();
        $userId         = session()->get('user_id');

        if (!in_array($userRole, ['admin', 'kadiv'], true)) {
            return redirect()->to(site_url('dashboard/tasks'))->with('error', 'Hanya Admin BPH atau Kepala Divisi yang dapat membuat program kerja.');
        }

        $rules = [
            'title'       => 'required|min_length[3]|max_length[255]',
            'period_year' => 'required|exact_length[4]|numeric',
        ];

        if ($userRole === 'admin') {
            $rules['division_id'] = 'required|is_natural_no_zero';
        }

        if (!$this->validate($rules)) {
            return redirect()->to(site_url('dashboard/tasks'))->withInput()->with('errors', $this->validator->getErrors());
        }

        $divisionId = ($userRole === 'admin')
            ? (int) $this->request->getPost('division_id')
            : (int) $userDivisionId;

        $data = [
            'title'       => trim((string) $this->request->getPost('title')),
            'description' => trim((string) ($this->request->getPost('description') ?? '')),
            'division_id' => $divisionId,
            'period_year' => (int) $this->request->getPost('period_year'),
            'period_term' => 1,
            'start_date'  => $this->request->getPost('start_date') ?: null,
            'end_date'    => $this->request->getPost('end_date') ?: null,
            'status'      => 'active',
            'progress'    => 0,
            'created_by'  => $userId,
        ];

        $this->programModel->insert($data);
        $newProgramId = $this->programModel->getInsertID();

        return redirect()->to(site_url('dashboard/tasks?program_id=' . $newProgramId))->with('success', 'Program Kerja baru berhasil dibuat! Anda sekarang dapat menambahkan tugas tim di papan Kanban.');
    }

    public function store()
    {
        $rules = [
            'title'           => 'required|min_length[3]|max_length[255]',
            'work_program_id' => 'required|is_natural_no_zero',
            'priority'        => 'required|in_list[low,medium,high,critical]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(site_url('dashboard/tasks'))->withInput()->with('errors', $this->validator->getErrors());
        }

        $programId = (int) $this->request->getPost('work_program_id');
        $program   = $this->programModel->find($programId);

        if (!$this->canAccessProgram($program)) {
            return redirect()->to(site_url('dashboard/tasks'))->with('error', 'Akses ditolak. Anda tidak dapat menambahkan tugas ke program kerja divisi lain.');
        }

        $userId = session()->get('user_id');

        $data = [
            'work_program_id' => $programId,
            'title'           => trim((string) $this->request->getPost('title')),
            'description'     => trim((string) ($this->request->getPost('description') ?? '')),
            'status'          => 'todo',
            'priority'        => $this->request->getPost('priority'),
            'assigned_to'     => $this->request->getPost('assigned_to') ? (int) $this->request->getPost('assigned_to') : null,
            'due_date'        => $this->request->getPost('due_date') ? $this->request->getPost('due_date') : null,
            'created_by'      => $userId,
        ];

        $this->taskModel->insert($data);

        // Update progress persentase proker
        $this->taskModel->updateProgramProgress($programId);

        return redirect()->to(site_url('dashboard/tasks?program_id=' . $programId))->with('success', 'Tugas baru berhasil ditambahkan ke kolom To Do!');
    }

    public function updateStatus()
    {
        $taskId    = (int) $this->request->getPost('task_id');
        $newStatus = $this->request->getPost('status');

        $validStatuses = ['todo', 'in_progress', 'review', 'done'];
        if (!in_array($newStatus, $validStatuses, true)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Status tidak valid']);
            }
            return redirect()->to(site_url('dashboard/tasks'))->with('error', 'Status tidak valid');
        }

        $task = $this->taskModel->find($taskId);
        if (!$task) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tugas tidak ditemukan']);
            }
            return redirect()->to(site_url('dashboard/tasks'))->with('error', 'Tugas tidak ditemukan');
        }

        $program = $this->programModel->find((int) $task['work_program_id']);
        if (!$this->canAccessProgram($program)) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
            }
            return redirect()->to(site_url('dashboard/tasks'))->with('error', 'Akses ditolak. Anda tidak dapat mengubah tugas milik divisi lain.');
        }

        $oldStatus = $task['status'];
        $this->taskModel->update($taskId, ['status' => $newStatus]);

        // Auto-log status change
        $userId = session()->get('user_id');
        $this->logModel->insert([
            'task_id'         => $taskId,
            'user_id'         => $userId,
            'body'            => "Status diubah dari '" . strtoupper($oldStatus) . "' menjadi '" . strtoupper($newStatus) . "'.",
            'status_snapshot' => $newStatus,
            'attachment'      => null,
            'logged_date'     => date('Y-m-d'),
        ]);

        // Hitung ulang progress program kerja
        $newProgress = $this->taskModel->updateProgramProgress((int) $task['work_program_id']);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'       => 'success',
                'message'      => 'Status tugas diperbarui',
                'new_progress' => $newProgress,
            ]);
        }

        return redirect()->to(site_url('dashboard/tasks?program_id=' . (int) $task['work_program_id']))
                         ->with('success', 'Status tugas berhasil diperbarui ke ' . strtoupper($newStatus) . '!');
    }

    public function addProgressLog()
    {
        $taskId = (int) $this->request->getPost('task_id');
        $body   = trim((string) ($this->request->getPost('body') ?? ''));

        if (empty($body)) {
            return redirect()->to(site_url('dashboard/tasks'))->with('error', 'Catatan progres tidak boleh kosong.');
        }

        $task = $this->taskModel->find($taskId);
        if (!$task) {
            return redirect()->to(site_url('dashboard/tasks'))->with('error', 'Tugas tidak ditemukan.');
        }

        $program = $this->programModel->find((int) $task['work_program_id']);
        if (!$this->canAccessProgram($program)) {
            return redirect()->to(site_url('dashboard/tasks'))->with('error', 'Akses ditolak. Anda tidak dapat melapor progres pada tugas divisi lain.');
        }

        $rawAttachment = trim((string) ($this->request->getPost('attachment') ?? ''));
        $attachment    = $this->sanitizeHttpUrl($rawAttachment);
        if ($rawAttachment !== '' && $attachment === null) {
            return redirect()->to(site_url('dashboard/tasks?program_id=' . (int) $task['work_program_id']))
                             ->with('error', 'Tautan lampiran harus berupa URL valid berawalan http:// atau https://.');
        }

        $newStatus     = $this->request->getPost('status_update');
        $currentStatus = $task['status'];

        if (!empty($newStatus) && in_array($newStatus, ['todo', 'in_progress', 'review', 'done'], true)) {
            $this->taskModel->update($taskId, ['status' => $newStatus]);
            $currentStatus = $newStatus;
        }

        $userId = session()->get('user_id');
        $this->logModel->insert([
            'task_id'         => $taskId,
            'user_id'         => $userId,
            'body'            => $body,
            'status_snapshot' => $currentStatus,
            'attachment'      => $attachment,
            'logged_date'     => date('Y-m-d'),
        ]);

        // Update progress proker
        $this->taskModel->updateProgramProgress((int) $task['work_program_id']);

        return redirect()->to(site_url('dashboard/tasks?program_id=' . (int) $task['work_program_id']))
                         ->with('success', 'Laporan progres tugas berhasil dicatat!');
    }
}
