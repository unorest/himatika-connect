<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $divisions = [
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

        $this->db->disableForeignKeyChecks();
        $this->db->table('hmt_divisions')->truncate();
        $this->db->table('hmt_divisions')->insertBatch($divisions);
        $this->db->enableForeignKeyChecks();

        echo "✅ DivisionSeeder: 4 divisi resmi (Stupport, PDD, PDSM, Intellogy) berhasil ditambahkan.\n";
    }
}
