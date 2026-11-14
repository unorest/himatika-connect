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
                'name'        => 'Badan Pengurus Harian',
                'slug'        => 'bph',
                'description' => 'Badan Pengurus Harian HIMATIKA yang bertanggung jawab atas koordinasi dan pengambilan keputusan organisasi.',
                'color'       => '#1D4ED8',
                'icon'        => 'shield-check',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi Akademik',
                'slug'        => 'akademik',
                'description' => 'Bertanggung jawab atas program kerja yang berkaitan dengan kegiatan akademik, tutoring, dan pengembangan intelektual anggota.',
                'color'       => '#059669',
                'icon'        => 'academic-cap',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi Humas & Komunikasi',
                'slug'        => 'humas',
                'description' => 'Mengelola hubungan masyarakat, komunikasi eksternal, media sosial, dan publikasi kegiatan himpunan.',
                'color'       => '#D97706',
                'icon'        => 'megaphone',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi Advokasi & Kesejahteraan',
                'slug'        => 'advokasi',
                'description' => 'Menampung dan menindaklanjuti aspirasi mahasiswa, serta memastikan kesejahteraan anggota himpunan.',
                'color'       => '#DC2626',
                'icon'        => 'hand-raised',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi PSDM',
                'slug'        => 'psdm',
                'description' => 'Pengembangan Sumber Daya Mahasiswa — bertanggung jawab atas kaderisasi, pelatihan, dan pengembangan karakter anggota.',
                'color'       => '#7C3AED',
                'icon'        => 'users',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi Kewirausahaan',
                'slug'        => 'kewirausahaan',
                'description' => 'Mengelola kegiatan kewirausahaan, usaha himpunan, dan pengembangan jiwa entrepreneurship anggota.',
                'color'       => '#EA580C',
                'icon'        => 'currency-dollar',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi Teknologi & Inovasi',
                'slug'        => 'teknologi',
                'description' => 'Mengembangkan proyek teknologi, kompetisi programming, dan inovasi berbasis teknologi informasi.',
                'color'       => '#0891B2',
                'icon'        => 'computer-desktop',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Divisi Pengabdian Masyarakat',
                'slug'        => 'pengabdian',
                'description' => 'Merancang dan menjalankan program pengabdian kepada masyarakat sebagai bentuk kontribusi sosial himpunan.',
                'color'       => '#16A34A',
                'icon'        => 'heart',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        $this->db->table('hmt_divisions')->insertBatch($divisions);
        echo "✅ DivisionSeeder: " . count($divisions) . " divisi berhasil ditambahkan.\n";
    }
}
