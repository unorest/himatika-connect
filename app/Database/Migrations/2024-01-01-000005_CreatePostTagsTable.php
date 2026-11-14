<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostTagsTable extends Migration
{
    public function up(): void
    {
        // Tags table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('name');
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('hmt_post_tags');

        // Pivot table
        $this->forge->addField([
            'post_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tag_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
        $this->forge->addPrimaryKey(['post_id', 'tag_id']);
        $this->forge->addForeignKey('post_id', 'hmt_posts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'hmt_post_tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hmt_post_tag_pivot');
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_post_tag_pivot', true);
        $this->forge->dropTable('hmt_post_tags', true);
    }
}
