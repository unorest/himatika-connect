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

    public function index()
    {
        $userDivisionId = session()->get('user_division_id');
        $userRole       = session()->get('user_role');

        $programId = $this->request->getGet('program_id') ? (int) $this->request->getGet('program_id') : null;

        // Ambil daftar program kerja yang bisa diakses
        $programsQuery = $this->programModel->select('hmt_work_programs.*, hmt_divisions.name as division_name')
                                            ->join('hmt_divisions', 'hmt_divisions.id = hmt_work_programs.division_id', 'left')
                                            ->where('hmt_work_programs.deleted_at', null);

        if ($userRole !== 'admin' && !empty($userDivisionId)) {
            $programsQuery->where('hmt_work_programs.division_id', $userDivisionId);
        }
        $programs = $programsQuery->findAll();

        // Jika belum ada program_id yang dipilih dan ada program, pilih program pertama sebagai default
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
        if ($userRole !== 'admin' && !empty($userDivisionId)) {
            $membersQuery->where('division_id', $userDivisionId);
        }
        $members = $membersQuery->findAll();

        $data = [
            'title'         => 'Task Tracker Kanban — HIMATIKA Hub',
            'kanban'        => $kanban,
            'programs'      => $programs,
            'selectedProgramId' => $programId,
            'activeProgram' => $activeProgram,
            'members'       => $members,
        ];

        return view('dashboard/tasks/index', $data);
    }

    public function store()
    {
        $rules = [
            'title'           => 'required|min_length[3]|max_length[255]',
            'work_program_id' => 'required|is_natural_no_zero',
            'priority'        => 'required|in_list[low,medium,high,critical]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $programId = (int) $this->request->getPost('work_program_id');
        $userId    = session()->get('user_id');

        $data = [
            'work_program_id' => $programId,
            'title'           => trim($this->request->getPost('title')),
            'description'     => trim($this->request->getPost('description') ?? ''),
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
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $task = $this->taskModel->find($taskId);
        if (!$task) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tugas tidak ditemukan']);
            }
            return redirect()->back()->with('error', 'Tugas tidak ditemukan');
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

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui ke ' . strtoupper($newStatus) . '!');
    }

    public function addProgressLog()
    {
        $taskId = (int) $this->request->getPost('task_id');
        $body   = trim($this->request->getPost('body') ?? '');

        if (empty($body)) {
            return redirect()->back()->with('error', 'Catatan progres tidak boleh kosong.');
        }

        $task = $this->taskModel->find($taskId);
        if (!$task) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan.');
        }

        $newStatus = $this->request->getPost('status_update');
        $currentStatus = $task['status'];

        if (!empty($newStatus) && in_array($newStatus, ['todo', 'in_progress', 'review', 'done'])) {
            $this->taskModel->update($taskId, ['status' => $newStatus]);
            $currentStatus = $newStatus;
        }

        $userId = session()->get('user_id');
        $this->logModel->insert([
            'task_id'         => $taskId,
            'user_id'         => $userId,
            'body'            => $body,
            'status_snapshot' => $currentStatus,
            'attachment'      => trim($this->request->getPost('attachment') ?? ''),
            'logged_date'     => date('Y-m-d'),
        ]);

        // Update progress proker
        $this->taskModel->updateProgramProgress((int) $task['work_program_id']);

        return redirect()->back()->with('success', 'Laporan progres tugas berhasil dicatat!');
    }
}
