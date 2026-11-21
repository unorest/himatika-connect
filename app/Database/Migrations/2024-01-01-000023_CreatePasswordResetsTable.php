<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePasswordResetsTable extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom phone ke hmt_users jika belum ada
        if ($this->db->tableExists('hmt_users') && !$this->db->fieldExists('phone', 'hmt_users')) {
            try {
                $this->forge->addColumn('hmt_users', [
                    'phone' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 30,
                        'null'       => true,
                        'after'      => 'email',
                    ],
                ]);
            } catch (\Throwable $e) {}
        }

        // 2. Buat tabel hmt_password_resets
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64, // SHA-256 hash (64 hex characters) dari 32-byte cryptographically secure random token
                'null'       => false,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 64, // SHA-256 hash of short code
                'null'       => true,
            ],
            'plain_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'channel' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'whatsapp',
            ],
            'target' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('token');
        $this->forge->addKey('email');
        $this->forge->addKey('plain_code');
        $this->forge->addKey('expires_at');

        $this->forge->createTable('hmt_password_resets', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_password_resets', true);

        if ($this->db->tableExists('hmt_users') && $this->db->fieldExists('phone', 'hmt_users')) {
            try {
                $this->forge->dropColumn('hmt_users', 'phone');
            } catch (\Throwable $e) {}
        }
    }
}
