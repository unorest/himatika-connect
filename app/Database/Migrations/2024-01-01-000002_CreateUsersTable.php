<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'nim' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'avatar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'kadiv', 'staff'],
                'default'    => 'staff',
            ],
            'division_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'remember_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
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
        $this->forge->addUniqueKey('nim');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('division_id');
        $this->forge->addForeignKey('division_id', 'hmt_divisions', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('hmt_users');

        // Add FK back to divisions for head_user_id
        $this->db->query('ALTER TABLE hmt_divisions ADD CONSTRAINT fk_divisions_head_user FOREIGN KEY (head_user_id) REFERENCES hmt_users(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE hmt_divisions DROP FOREIGN KEY fk_divisions_head_user');
        $this->forge->dropTable('hmt_users', true);
    }
}
