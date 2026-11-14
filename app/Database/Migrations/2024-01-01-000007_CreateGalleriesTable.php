<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGalleriesTable extends Migration
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
            'post_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'caption' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'sort_order' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'default'    => 0,
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
        $this->forge->addKey('post_id');
        $this->forge->addKey('event_id');
        $this->forge->addForeignKey('post_id', 'hmt_posts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('event_id', 'hmt_events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hmt_galleries');
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_galleries', true);
    }
}
