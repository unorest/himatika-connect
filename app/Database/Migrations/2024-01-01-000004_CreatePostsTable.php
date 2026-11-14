<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostsTable extends Migration
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
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 300,
                'null'       => false,
            ],
            'body' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'excerpt' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'thumbnail' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'author_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'published', 'archived'],
                'default'    => 'draft',
            ],
            'read_time' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'default'    => 1,
            ],
            'views' => [
                'type'     => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'default'  => 0,
            ],
            'published_at' => [
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
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('author_id');
        $this->forge->addKey('category_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('author_id', 'hmt_users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'hmt_categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('hmt_posts');
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_posts', true);
    }
}
