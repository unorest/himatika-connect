<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $admin = $this->db->table('hmt_users')->where('email', 'admin@himatika.org')->get()->getRowArray();
        $tekno = $this->db->table('hmt_users')->where('email', 'teknologi@himatika.org')->get()->getRowArray();
        $advok = $this->db->table('hmt_users')->where('email', 'advokasi@himatika.org')->get()->getRowArray();

        $divisions = $this->db->table('hmt_divisions')->get()->getResultArray();
        $divMap = [];
        foreach ($divisions as $d) {
            $divMap[$d['slug']] = $d['id'];
        }

        $schedules = [
            [
                'title'          => 'Rapat Pleno Bulanan Evaluasi Kinerja Tengah Periode',
                'description'    => 'Membahas evaluasi proker setiap divisi, kendala operasional sekretariat, dan penyelarasan timeline menuju perayaan Dies Natalis.',
                'division_id'    => null, // Pleno umum seluruh himpunan
                'start_datetime' => date('Y-m-d 16:00:00', strtotime('+2 days')),
                'end_datetime'   => date('Y-m-d 18:30:00', strtotime('+2 days')),
                'location'       => 'Auditorium Gd. B & Hybrid Zoom',
                'color'          => '#1D4ED8',
                'is_recurring'   => 1,
                'created_by'     => $admin['id'] ?? 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'title'          => 'Rapat Koordinasi Teknis Proker HIMATIKA Hub & Hackfest',
                'description'    => 'Sinkronisasi progress deployment server dan modul form pendaftaran peserta lomba hackathon.',
                'division_id'    => $divMap['teknologi'] ?? 7,
                'start_datetime' => date('Y-m-d 13:30:00', strtotime('+4 days')),
                'end_datetime'   => date('Y-m-d 15:30:00', strtotime('+4 days')),
                'location'       => 'Lab Komputer 2',
                'color'          => '#0891B2',
                'is_recurring'   => 0,
                'created_by'     => $tekno['id'] ?? $admin['id'] ?? 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'title'          => 'Batas Waktu (Deadline) Pengumpulan Draft LPJ Triwulan 1',
                'description'    => 'Seluruh bendahara divisi wajib mengumpulkan rekapitulasi nota dan laporan pertanggungjawaban fisik ke Bendahara Umum.',
                'division_id'    => $divMap['bph'] ?? 1,
                'start_datetime' => date('Y-m-d 23:59:59', strtotime('+7 days')),
                'end_datetime'   => date('Y-m-d 23:59:59', strtotime('+7 days')),
                'location'       => 'Google Drive Sekretariat BPH',
                'color'          => '#DC2626',
                'is_recurring'   => 0,
                'created_by'     => $admin['id'] ?? 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ];

        $users = $this->db->table('hmt_users')->where('is_active', 1)->get()->getResultArray();

        foreach ($schedules as $s) {
            $this->db->table('hmt_schedules')->insert($s);
            $schId = $this->db->insertID();

            // Insert RSVP attendees
            $attendeesBatch = [];
            foreach ($users as $u) {
                $attendeesBatch[] = [
                    'schedule_id' => $schId,
                    'user_id'     => $u['id'],
                    'rsvp'        => ($u['id'] == $s['created_by']) ? 'accepted' : 'pending',
                    'created_at'  => $now,
                ];
            }
            if (!empty($attendeesBatch)) {
                $this->db->table('hmt_schedule_attendees')->insertBatch($attendeesBatch);
            }
        }

        echo "✅ ScheduleSeeder: Agenda rapat internal dan peserta berhasil ditambahkan.\n";
    }
}
