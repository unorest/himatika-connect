<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table            = 'hmt_tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'work_program_id', 'title', 'description', 'status', 'priority',
        'assigned_to', 'due_date', 'sort_order', 'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getKanbanTasks(?int $programId = null, ?int $divisionId = null)
    {
        $builder = $this->select('hmt_tasks.*, hmt_work_programs.title as program_title, hmt_users.name as assignee_name, hmt_users.avatar as assignee_avatar, creator.name as creator_name, hmt_divisions.name as division_name, hmt_divisions.color as division_color')
                        ->join('hmt_work_programs', 'hmt_work_programs.id = hmt_tasks.work_program_id', 'left')
                        ->join('hmt_divisions', 'hmt_divisions.id = hmt_work_programs.division_id', 'left')
                        ->join('hmt_users', 'hmt_users.id = hmt_tasks.assigned_to', 'left')
                        ->join('hmt_users as creator', 'creator.id = hmt_tasks.created_by', 'left')
                        ->where('hmt_tasks.deleted_at', null);

        if (!empty($programId)) {
            $builder->where('hmt_tasks.work_program_id', $programId);
        }

        if (!empty($divisionId)) {
            $builder->where('hmt_work_programs.division_id', $divisionId);
        }

        $allTasks = $builder->orderBy('hmt_tasks.sort_order', 'ASC')
                            ->orderBy('hmt_tasks.created_at', 'DESC')
                            ->findAll();

        // Ambil logs untuk masing-masing task
        $taskIds = array_column($allTasks, 'id');
        $logsByTaskId = [];
        if (!empty($taskIds)) {
            $logs = $this->db->table('hmt_task_progress_logs')
                             ->select('hmt_task_progress_logs.*, hmt_users.name as author_name')
                             ->join('hmt_users', 'hmt_users.id = hmt_task_progress_logs.user_id', 'left')
                             ->whereIn('task_id', $taskIds)
                             ->orderBy('created_at', 'DESC')
                             ->get()
                             ->getResultArray();

            foreach ($logs as $log) {
                $logsByTaskId[$log['task_id']][] = $log;
            }
        }

        // Kelompokkan ke 4 status kanban
        $kanban = [
            'todo'        => [],
            'in_progress' => [],
            'review'      => [],
            'done'        => [],
        ];

        foreach ($allTasks as $task) {
            $task['logs'] = $logsByTaskId[$task['id']] ?? [];
            $status = $task['status'];
            if (isset($kanban[$status])) {
                $kanban[$status][] = $task;
            } else {
                $kanban['todo'][] = $task;
            }
        }

        return $kanban;
    }

    /**
     * Hitung ulang persentase progress proker berdasarkan rasio task done/total
     */
    public function updateProgramProgress(int $programId)
    {
        $total = $this->where('work_program_id', $programId)->countAllResults();
        if ($total === 0) {
            $this->db->table('hmt_work_programs')->where('id', $programId)->update(['progress' => 0]);
            return 0;
        }

        $done = $this->where('work_program_id', $programId)->where('status', 'done')->countAllResults();
        $percent = (int) round(($done / $total) * 100);

        $this->db->table('hmt_work_programs')->where('id', $programId)->update(['progress' => $percent]);
        return $percent;
    }
}
