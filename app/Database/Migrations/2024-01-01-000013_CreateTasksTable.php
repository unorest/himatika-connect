<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksTable extends Migration
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
            'work_program_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['todo', 'in_progress', 'review', 'done'],
                'default'    => 'todo',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default'    => 'medium',
            ],
            'assigned_to' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'sort_order' => [
                'type'     => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'default'  => 0,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('work_program_id');
        $this->forge->addKey('assigned_to');
        $this->forge->addKey('created_by');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('work_program_id', 'hmt_work_programs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assigned_to', 'hmt_users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'hmt_users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hmt_tasks');
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_tasks', true);
    }
}
