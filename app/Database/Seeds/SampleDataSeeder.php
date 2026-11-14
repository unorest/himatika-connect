<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // Ambil User & Category IDs
        $admin = $this->db->table('hmt_users')->where('email', 'admin@himatika.org')->get()->getRowArray();
        $advokasiUser = $this->db->table('hmt_users')->where('email', 'advokasi@himatika.org')->get()->getRowArray();
        $humasUser = $this->db->table('hmt_users')->where('email', 'humas@himatika.org')->get()->getRowArray();
        $teknoUser = $this->db->table('hmt_users')->where('email', 'teknologi@himatika.org')->get()->getRowArray();

        $categories = $this->db->table('hmt_categories')->get()->getResultArray();
        $catMap = [];
        foreach ($categories as $c) {
            $catMap[$c['slug']] = $c['id'];
        }

        $divisions = $this->db->table('hmt_divisions')->get()->getResultArray();
        $divMap = [];
        foreach ($divisions as $d) {
            $divMap[$d['slug']] = $d['id'];
        }

        // 1. Sample Posts (Blog)
        $posts = [
            [
                'title'        => 'Pelantikan Pengurus HIMATIKA Periode 2026/2027: Siap Berinovasi!',
                'slug'         => 'pelantikan-pengurus-himatika-periode-2026-2027',
                'body'         => '<p>Selamat datang di kepengurusan baru HIMATIKA Connect! Melalui pelantikan ini, segenap pengurus berkomitmen menghadirkan transformasi digital, transparansi aspirasi, dan peningkatan kualitas akademik seluruh mahasiswa Informatika.</p><p>Mari berkolaborasi bersama membangun himpunan yang inklusif dan progresif.</p>',
                'excerpt'      => 'Pelantikan resmi pengurus baru HIMATIKA Connect dengan semangat sinergi dan inovasi digital.',
                'thumbnail'    => null,
                'author_id'    => $humasUser['id'] ?? $admin['id'],
                'category_id'  => $catMap['kegiatan-event'] ?? null,
                'status'       => 'published',
                'read_time'    => 3,
                'views'        => 120,
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'title'        => 'Tips Sukses Menghadapi Sidang Skripsi & Tugas Akhir Bidang Komputasi',
                'slug'         => 'tips-sukses-menghadapi-sidang-skripsi-tugas-akhir',
                'body'         => '<p>Menghadapi tugas akhir bukan sekadar menulis kode, tetapi bagaimana menyusun metodologi riset, pengujian algoritma, serta komunikasi hasil analisis secara sistematis di hadapan dewan penguji.</p><p>Simak panduan komprehensif mulai dari penyusunan proposal hingga gladi resik presentasi.</p>',
                'excerpt'      => 'Panduan esensial bagi mahasiswa tingkat akhir dalam menyusun laporan dan presentasi sidang skripsi.',
                'thumbnail'    => null,
                'author_id'    => $admin['id'],
                'category_id'  => $catMap['tech-tutorial'] ?? null,
                'status'       => 'published',
                'read_time'    => 5,
                'views'        => 245,
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];
        $this->db->table('hmt_posts')->insertBatch($posts);

        // 2. Sample Events
        $events = [
            [
                'title'                 => 'HIMATIKA Hackfest 2026: AI & Web Innovation Challenge',
                'slug'                  => 'himatika-hackfest-2026',
                'description'           => 'Kompetisi hackathon 24 jam untuk mengembangkan solusi berbasis web dan kecerdasan buatan dalam memecahkan masalah civitas kampus.',
                'location'              => 'Auditorium Lab Komputer Gedung B & Hybrid Zoom',
                'start_datetime'        => date('Y-m-d 08:30:00', strtotime('+10 days')),
                'end_datetime'          => date('Y-m-d 17:00:00', strtotime('+11 days')),
                'category_id'           => $catMap['lomba-kompetisi'] ?? null,
                'division_id'           => $divMap['teknologi'] ?? null,
                'color'                 => '#2563EB',
                'is_public'             => 1,
                'allow_registration'    => 1,
                'max_participants'      => 100,
                'registration_deadline' => date('Y-m-d 23:59:59', strtotime('+8 days')),
                'created_by'            => $teknoUser['id'] ?? $admin['id'],
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
            [
                'title'                 => 'Workshop Cloud Computing & DevOps Essentials',
                'slug'                  => 'workshop-cloud-computing-devops-essentials',
                'description'           => 'Hands-on workshop pengenalan Docker containerization, CI/CD pipelines, dan deployment cloud modern.',
                'location'              => 'Lab Komputer 3',
                'start_datetime'        => date('Y-m-d 13:00:00', strtotime('+18 days')),
                'end_datetime'          => date('Y-m-d 16:30:00', strtotime('+18 days')),
                'category_id'           => $catMap['workshop-pelatihan'] ?? null,
                'division_id'           => $divMap['teknologi'] ?? null,
                'color'                 => '#10B981',
                'is_public'             => 1,
                'allow_registration'    => 1,
                'max_participants'      => 40,
                'registration_deadline' => date('Y-m-d 23:59:59', strtotime('+16 days')),
                'created_by'            => $teknoUser['id'] ?? $admin['id'],
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
        ];
        $this->db->table('hmt_events')->insertBatch($events);

        // 3. Sample Forum Questions (Aspirasi & Q&A)
        $q1 = [
            'title'        => 'Bagaimana alur pengajuan surat pengantar kerja praktik (KP) tahun ini?',
            'body'         => 'Halo pengurus, apakah permohonan surat izin KP ke prodi sudah bisa lewat sistem online penuh atau masih harus tanda tangan basah di sekretariat jurusan?',
            'author_name'  => 'Ilham Pratama',
            'author_nim'   => '23010199',
            'author_email' => 'ilham@student.ac.id',
            'is_anonymous' => 0,
            'category_id'  => $catMap['birokrasi-jurusan'] ?? null,
            'status'       => 'answered',
            'upvotes'      => 12,
            'ip_address'   => '127.0.0.1',
            'is_pinned'    => 1,
            'moderated_by' => $advokasiUser['id'] ?? $admin['id'],
            'moderated_at' => $now,
            'created_at'   => date('Y-m-d H:i:s', strtotime('-3 days')),
            'updated_at'   => $now,
        ];
        $this->db->table('hmt_forum_questions')->insert($q1);
        $q1Id = $this->db->insertID();

        // Jawaban untuk q1
        $this->db->table('hmt_forum_answers')->insert([
            'question_id' => $q1Id,
            'body'        => 'Halo Ilham! Untuk periode 2026, pengajuan surat pengantar KP sudah 100% digital melalui portal fakultas menu Layanan Akademik > Persuratan KP. Silakan unduh template dokumen yang telah disediakan di website himpunan bagian arsip.',
            'answered_by' => $advokasiUser['id'] ?? $admin['id'],
            'is_official' => 1,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $q2 = [
            'title'        => 'Usulan penambahan stopkontak dan colokan listrik di area Lab Software',
            'body'         => 'Saat kuliah praktikum siang, banyak mahasiswa yang kehabisan colokan untuk charger laptop. Mohon bantuannya untuk diajukan ke bagian sarpras kampus.',
            'author_name'  => 'Anonim Mahasiswa',
            'author_nim'   => null,
            'author_email' => null,
            'is_anonymous' => 1,
            'category_id'  => $catMap['fasilitas-lab'] ?? null,
            'status'       => 'pending',
            'upvotes'      => 8,
            'ip_address'   => '127.0.0.1',
            'is_pinned'    => 0,
            'moderated_by' => null,
            'moderated_at' => null,
            'created_at'   => date('Y-m-d H:i:s', strtotime('-1 days')),
            'updated_at'   => $now,
        ];
        $this->db->table('hmt_forum_questions')->insert($q2);

        // 4. Sample Program Kerja & Task Tracker (Internal Kanban)
        $proker = [
            'title'        => 'Pengembangan Website HIMATIKA Hub',
            'description'  => 'Membangun portal informasi terintegrasi dan dashboard monitoring proker untuk kepengurusan HIMATIKA.',
            'division_id'  => $divMap['teknologi'] ?? 1,
            'period_year'  => date('Y'),
            'period_term'  => 1,
            'start_date'   => date('Y-m-d', strtotime('-10 days')),
            'end_date'     => date('Y-m-d', strtotime('+30 days')),
            'status'       => 'active',
            'progress'     => 33,
            'created_by'   => $teknoUser['id'] ?? $admin['id'],
            'created_at'   => $now,
            'updated_at'   => $now,
        ];
        $this->db->table('hmt_work_programs')->insert($proker);
        $prokerId = $this->db->insertID();

        // Tasks Kanban
        $tasks = [
            [
                'work_program_id' => $prokerId,
                'title'           => 'Perancangan Skema Database (ERD) & Migrasi',
                'description'     => 'Menyusun skema 18 tabel dan menyiapkan migrasi CodeIgniter 4.',
                'status'          => 'done',
                'priority'        => 'high',
                'assigned_to'     => $teknoUser['id'] ?? $admin['id'],
                'due_date'        => date('Y-m-d', strtotime('+1 days')),
                'sort_order'      => 1,
                'created_by'      => $admin['id'],
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'work_program_id' => $prokerId,
                'title'           => 'Pembuatan Halaman Landing Page Publik & Formulir Q&A',
                'description'     => 'Implementasi UI modern, responsif, dan interaktif untuk umum.',
                'status'          => 'in_progress',
                'priority'        => 'high',
                'assigned_to'     => $teknoUser['id'] ?? $admin['id'],
                'due_date'        => date('Y-m-d', strtotime('+5 days')),
                'sort_order'      => 2,
                'created_by'      => $admin['id'],
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'work_program_id' => $prokerId,
                'title'           => 'Integrasi Notifikasi Webhook Discord/WhatsApp',
                'description'     => 'Mengirim notifikasi pertanyaan aspirasi baru ke channel koordinasi himpunan.',
                'status'          => 'todo',
                'priority'        => 'medium',
                'assigned_to'     => $teknoUser['id'] ?? $admin['id'],
                'due_date'        => date('Y-m-d', strtotime('+12 days')),
                'sort_order'      => 3,
                'created_by'      => $admin['id'],
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];
        $this->db->table('hmt_tasks')->insertBatch($tasks);

        echo "✅ SampleDataSeeder: Posts, Events, Forum Q&A, dan Proker Tasks berhasil ditambahkan.\n";
    }
}
