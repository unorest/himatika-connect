<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaskProgressLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'task_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'status_snapshot' => [
                'type'       => 'ENUM',
                'constraint' => ['todo', 'in_progress', 'review', 'done'],
                'null'       => false,
            ],
            'attachment' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'logged_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('task_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('logged_date');
        $this->forge->addForeignKey('task_id', 'hmt_tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'hmt_users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hmt_task_progress_logs');
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_task_progress_logs', true);
    }
}
