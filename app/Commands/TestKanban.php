<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\TaskModel;

class TestKanban extends BaseCommand
{
    protected $group       = 'Himatika';
    protected $name        = 'himatika:test-kanban';
    protected $description = 'Memeriksa fungsi query Kanban dan kalkulasi progress proker.';

    public function run(array $params)
    {
        $taskModel = new TaskModel();
        $kanban = $taskModel->getKanbanTasks(1);

        CLI::write("Status Kanban Program #1:", 'yellow');
        CLI::write(" - To Do: " . count($kanban['todo']) . " tugas", 'white');
        CLI::write(" - In Progress: " . count($kanban['in_progress']) . " tugas", 'cyan');
        CLI::write(" - Review: " . count($kanban['review']) . " tugas", 'yellow');
        CLI::write(" - Done: " . count($kanban['done']) . " tugas", 'green');

        $percent = $taskModel->updateProgramProgress(1);
        CLI::write("Progress Otomatis Proker #1: {$percent}%", 'green');
    }
}
