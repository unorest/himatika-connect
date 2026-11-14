<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScheduleAttendeesTable extends Migration
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
            'schedule_id' => [
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
            'rsvp' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'accepted', 'declined'],
                'default'    => 'pending',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['schedule_id', 'user_id']);
        $this->forge->addKey('schedule_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('schedule_id', 'hmt_schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'hmt_users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hmt_schedule_attendees');
    }

    public function down(): void
    {
        $this->forge->dropTable('hmt_schedule_attendees', true);
    }
}
