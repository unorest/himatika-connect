<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateForumAnswersTable extends Migration
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
            'question_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'answered_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'is_official' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey('question_id');
        $this->forge->addKey('answered_by');
        $this->forge->addForeignKey('question_id', 'hmt_forum_questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('answered_by', 'hmt_users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hmt_forum_answers');
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_forum_answers', true);
    }
}
