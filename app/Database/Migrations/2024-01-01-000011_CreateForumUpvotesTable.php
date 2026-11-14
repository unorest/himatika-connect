<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateForumUpvotesTable extends Migration
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
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => false,
            ],
            'session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['question_id', 'ip_address']);
        $this->forge->addKey('question_id');
        $this->forge->addForeignKey('question_id', 'hmt_forum_questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hmt_forum_upvotes');
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_forum_upvotes', true);
    }
}
