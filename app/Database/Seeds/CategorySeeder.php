<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $categories = [
            // Kategori Blog / Berita
            [
                'name'        => 'Kegiatan & Event',
                'slug'        => 'kegiatan-event',
                'type'        => 'blog',
                'description' => 'Liputan kegiatan, webinar, dan perayaan di lingkungan HIMATIKA.',
                'color'       => '#2563EB',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Prestasi Mahasiswa',
                'slug'        => 'prestasi-mahasiswa',
                'type'        => 'blog',
                'description' => 'Kabar prestasi membanggakan dari sivitas mahasiswa.',
                'color'       => '#EAB308',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Tech & Tutorial',
                'slug'        => 'tech-tutorial',
                'type'        => 'blog',
                'description' => 'Artikel edukatif dan wawasan perkembangan teknologi terkini.',
                'color'       => '#0D9488',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Informasi Akademik',
                'slug'        => 'informasi-akademik',
                'type'        => 'blog',
                'description' => 'Pengumuman resmi kurikulum, KRS, dan jadwal ujian.',
                'color'       => '#9333EA',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // Kategori Forum Aspirasi & Q&A
            [
                'name'        => 'Birokrasi & Jurusan',
                'slug'        => 'birokrasi-jurusan',
                'type'        => 'forum',
                'description' => 'Pertanyaan seputar administrasi, surat menyurat, dan fakultas.',
                'color'       => '#DC2626',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Mata Kuliah & Dosen',
                'slug'        => 'mata-kuliah-dosen',
                'type'        => 'forum',
                'description' => 'Diskusi seputar silabus, tugas, dan rekomendasi peminatan.',
                'color'       => '#F97316',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Fasilitas & Lab',
                'slug'        => 'fasilitas-lab',
                'type'        => 'forum',
                'description' => 'Aspirasi terkait sarana lab komputer, Wi-Fi, dan ruang himpunan.',
                'color'       => '#4F46E5',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Aspirasi Terbuka (Umum)',
                'slug'        => 'aspirasi-umum',
                'type'        => 'forum',
                'description' => 'Kritik, saran, atau pertanyaan bebas mahasiswa untuk himpunan.',
                'color'       => '#6B7280',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // Kategori Event
            [
                'name'        => 'Workshop & Pelatihan',
                'slug'        => 'workshop-pelatihan',
                'type'        => 'event',
                'description' => 'Sesi pelatihan skill teknis dan non-teknis.',
                'color'       => '#10B981',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Seminar Nasional',
                'slug'        => 'seminar-nasional',
                'type'        => 'event',
                'description' => 'Seminar bertaraf nasional bersama praktisi industri.',
                'color'       => '#6366F1',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Lomba & Kompetisi',
                'slug'        => 'lomba-kompetisi',
                'type'        => 'event',
                'description' => 'Hackathon, competitive programming, dan olimpiade.',
                'color'       => '#EC4899',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        $this->db->table('hmt_categories')->insertBatch($categories);
        echo "✅ CategorySeeder: " . count($categories) . " kategori berhasil ditambahkan.\n";
    }
}
