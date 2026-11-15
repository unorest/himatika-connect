<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateDivisionsToFour extends Migration
{
    public function up(): void
    {
        $this->db->disableForeignKeyChecks();

        $now = date('Y-m-d H:i:s');
        $newDivisions = [
            [
                'name'        => 'Divisi Stupport',
                'slug'        => 'stupport',
                'description' => 'Bertanggung jawab atas operasional, sarana prasarana, logistik perlengkapan, serta dukungan teknis kesekretariatan himpunan.',
                'color'       => '#10B981',
                'icon'        => 'life-ring',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi PDD',
                'slug'        => 'pdd',
                'description' => 'Publikasi, Dekorasi, & Dokumentasi — mengelola media sosial, branding visual, liputan dokumentasi kegiatan, dan dekorasi acara.',
                'color'       => '#F59E0B',
                'icon'        => 'camera-retro',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi PDSM',
                'slug'        => 'pdsm',
                'description' => 'Pengembangan Daya Saing Mahasiswa — bertanggung jawab atas kaderisasi, pelatihan kepemimpinan, dan peningkatan prestasi akademik anggota.',
                'color'       => '#8B5CF6',
                'icon'        => 'users-gear',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi Intellogy',
                'slug'        => 'intellogy',
                'description' => 'Intelligence & Technology — mengawal inovasi riset teknologi informasi, pengembangan perangkat lunak, dan kompetisi saintifik.',
                'color'       => '#06B6D4',
                'icon'        => 'microchip',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        // Kosongkan dan masukkan 4 divisi resmi
        $this->db->table('hmt_divisions')->truncate();
        $this->db->table('hmt_divisions')->insertBatch($newDivisions);

        // Ambil mapping ID divisi baru
        $divRows = $this->db->table('hmt_divisions')->get()->getResultArray();
        $divMap = [];
        foreach ($divRows as $d) {
            $divMap[$d['slug']] = $d['id'];
        }

        // Remap admin ke null (BPH / Pengurus Harian)
        $this->db->table('hmt_users')->where('role', 'admin')->update(['division_id' => null]);

        // Remap proker, event, dan jadwal
        if (isset($divMap['intellogy'])) {
            $this->db->table('hmt_work_programs')->update(['division_id' => $divMap['intellogy']]);
            $this->db->table('hmt_events')->update(['division_id' => $divMap['intellogy']]);
            $this->db->table('hmt_schedules')->where('division_id IS NOT NULL', null, false)->update(['division_id' => $divMap['intellogy']]);
        }

        // Remap akun-akun bawaan
        $userUpdates = [
            'akademik@himatika.org' => ['name' => 'Ahmad Fauzan (Kadiv PDSM)', 'email' => 'pdsm@himatika.org', 'div' => $divMap['pdsm'] ?? null],
            'advokasi@himatika.org' => ['name' => 'Siti Rahma (Kadiv Stupport)', 'email' => 'stupport@himatika.org', 'div' => $divMap['stupport'] ?? null],
            'humas@himatika.org'    => ['name' => 'Budi Santoso (Kadiv PDD)', 'email' => 'pdd@himatika.org', 'div' => $divMap['pdd'] ?? null],
            'teknologi@himatika.org'=> ['name' => 'Devina Putri (Kadiv Intellogy)', 'email' => 'intellogy@himatika.org', 'div' => $divMap['intellogy'] ?? null],
            'rian.staff@himatika.org'=> ['name' => 'Rian Pratama (Staff Stupport)', 'email' => 'staff.stupport@himatika.org', 'div' => $divMap['stupport'] ?? null],
            'farhan.staff@himatika.org'=> ['name' => 'Farhan Malik (Staff Intellogy)', 'email' => 'staff.intellogy@himatika.org', 'div' => $divMap['intellogy'] ?? null],
        ];

        foreach ($userUpdates as $oldEmail => $info) {
            $this->db->table('hmt_users')
                     ->where('email', $oldEmail)
                     ->orWhere('email', $info['email'])
                     ->update([
                         'name'        => $info['name'],
                         'email'       => $info['email'],
                         'division_id' => $info['div'],
                     ]);
        }

        // Remap remaining users yang division_id nya tidak ada di 4 divisi ke Stupport
        $validIds = array_values($divMap);
        if (!empty($validIds)) {
            $this->db->table('hmt_users')
                     ->where('role !=', 'admin')
                     ->whereNotIn('division_id', $validIds)
                     ->update(['division_id' => $divMap['stupport'] ?? $validIds[0]]);
        }

        $this->db->enableForeignKeyChecks();
    }

    public function down(): void
    {
        // No down migration needed
    }
}
