<?php

namespace App\Controllers;

use App\Models\WorkProgramModel;
use App\Models\ForumQuestionModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userId     = session()->get('user_id');
        $divisionId = session()->get('user_division_id');
        $role       = session()->get('user_role');

        $db = \Config\Database::connect();

        // 1. Ambil Task untuk User ini
        $myTasks = $db->table('hmt_tasks')
                      ->select('hmt_tasks.*, hmt_work_programs.title as program_title')
                      ->join('hmt_work_programs', 'hmt_work_programs.id = hmt_tasks.work_program_id', 'left')
                      ->where('hmt_tasks.assigned_to', $userId)
                      ->where('hmt_tasks.deleted_at', null)
                      ->orderBy('hmt_tasks.due_date', 'ASC')
                      ->get()
                      ->getResultArray();

        // 2. Ambil Program Kerja Divisi
        $prokerQuery = $db->table('hmt_work_programs')
                          ->select('hmt_work_programs.*, hmt_divisions.name as division_name, hmt_divisions.color as division_color')
                          ->join('hmt_divisions', 'hmt_divisions.id = hmt_work_programs.division_id', 'left')
                          ->where('hmt_work_programs.deleted_at', null);

        if ($role !== 'admin' && !empty($divisionId)) {
            $prokerQuery->where('hmt_work_programs.division_id', $divisionId);
        }
        $workPrograms = $prokerQuery->get()->getResultArray();

        // 3. Aspirasi Masuk yang Butuh Jawaban / Moderasi (Pending)
        $pendingQACount = $db->table('hmt_forum_questions')
                             ->where('status', 'pending')
                             ->where('deleted_at', null)
                             ->countAllResults();

        // 4. Hitung Statistik Singkat
        $stats = [
            'my_tasks_count'   => count($myTasks),
            'proker_count'     => count($workPrograms),
            'pending_qa_count' => $pendingQACount,
        ];

        $data = [
            'title'        => 'Workspace Dashboard — HIMATIKA Hub',
            'stats'        => $stats,
            'myTasks'      => $myTasks,
            'workPrograms' => $workPrograms,
        ];

        return view('dashboard/index', $data);
    }
}
