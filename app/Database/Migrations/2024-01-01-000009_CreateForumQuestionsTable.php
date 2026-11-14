<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateForumQuestionsTable extends Migration
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
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'author_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'author_nim' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'author_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'is_anonymous' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'answered', 'rejected'],
                'default'    => 'pending',
            ],
            'upvotes' => [
                'type'     => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'default'  => 0,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'is_pinned' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'moderated_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'moderated_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey('category_id');
        $this->forge->addKey('status');
        $this->forge->addKey('moderated_by');
        $this->forge->addForeignKey('category_id', 'hmt_categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('moderated_by', 'hmt_users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('hmt_forum_questions');
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_forum_questions', true);
    }
}
