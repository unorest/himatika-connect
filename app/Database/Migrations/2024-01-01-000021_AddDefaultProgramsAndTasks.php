<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDefaultProgramsAndTasks extends Migration
{
    public function up(): void
    {
        $now = date('Y-m-d H:i:s');
        $year = (int) date('Y');

        $divisions = $this->db->table('hmt_divisions')->get()->getResultArray();
        $divMap = [];
        foreach ($divisions as $d) {
            $divMap[$d['slug']] = (int) $d['id'];
        }

        $stupportId  = $divMap['stupport'] ?? 1;
        $pddId       = $divMap['pdd'] ?? 2;
        $psdmId      = $divMap['pdsm'] ?? ($divMap['psdm'] ?? 3);
        $intellogyId = $divMap['intellogy'] ?? 4;

        // Ambil ketua atau staff divisi
        $users = $this->db->table('hmt_users')->get()->getResultArray();
        $userMap = [];
        foreach ($users as $u) {
            $userMap[$u['nim']] = (int) $u['id'];
        }

        $defaultPrograms = [
            [
                'title'       => 'Inventarisasi & Pemeliharaan Logistik Himpunan',
                'description' => 'Pengadaan aset inventaris sekretariat, pemeliharaan perangkat, dan rekap sarana prasarana.',
                'division_id' => $stupportId,
                'period_year' => $year,
                'period_term' => 1,
                'start_date'  => date('Y-m-d', strtotime('-15 days')),
                'end_date'    => date('Y-m-d', strtotime('+45 days')),
                'status'      => 'active',
                'progress'    => 33,
                'created_by'  => $userMap['22010006'] ?? 1, // M. Rendy Amril
                'created_at'  => $now,
                'updated_at'  => $now,
                'tasks'       => [
                    [
                        'title'       => 'Pembuatan SOP Peminjaman Barang & Aset Himatika',
                        'description' => 'Menyusun alur peminjaman logistik sekretariat untuk kegiatan kemahasiswaan.',
                        'status'      => 'done',
                        'priority'    => 'medium',
                        'assigned_to' => $userMap['22010007'] ?? null, // Hikmah
                        'due_date'    => date('Y-m-d', strtotime('-5 days')),
                    ],
                    [
                        'title'       => 'Audit & Stock Opname Inventaris Ruang Himpunan',
                        'description' => 'Pengecekan fisik proyektor, kabel roll, printer, dan sound system sekretariat.',
                        'status'      => 'in_progress',
                        'priority'    => 'high',
                        'assigned_to' => $userMap['22010006'] ?? null, // Rendy
                        'due_date'    => date('Y-m-d', strtotime('+3 days')),
                    ],
                    [
                        'title'       => 'Pengadaan ATK & Kelengkapan Administrasi Triwulan',
                        'description' => 'Pembelian kertas, tinta stempel, dan map arsip persuratan himpunan.',
                        'status'      => 'todo',
                        'priority'    => 'medium',
                        'assigned_to' => $userMap['22010008'] ?? null, // Naufal
                        'due_date'    => date('Y-m-d', strtotime('+10 days')),
                    ],
                ]
            ],
            [
                'title'       => 'Publikasi Konten Branding & Dokumentasi Acara',
                'description' => 'Desain visual media sosial, pengelolaan feed Instagram, dan liputan dokumentasi kegiatan himpunan.',
                'division_id' => $pddId,
                'period_year' => $year,
                'period_term' => 1,
                'start_date'  => date('Y-m-d', strtotime('-10 days')),
                'end_date'    => date('Y-m-d', strtotime('+60 days')),
                'status'      => 'active',
                'progress'    => 33,
                'created_by'  => $userMap['22010010'] ?? 1, // Intan Puji
                'created_at'  => $now,
                'updated_at'  => $now,
                'tasks'       => [
                    [
                        'title'       => 'Perancangan Template Feed & Story Instagram Resmi',
                        'description' => 'Menyusun panduan color palette, typography, dan asset grafis HIMATIKA.',
                        'status'      => 'done',
                        'priority'    => 'high',
                        'assigned_to' => $userMap['22010010'] ?? null, // Intan
                        'due_date'    => date('Y-m-d', strtotime('-2 days')),
                    ],
                    [
                        'title'       => 'Liputan Dokumentasi & Rilis Foto Pelantikan Pengurus',
                        'description' => 'Editing foto kegiatan pelantikan untuk publikasi website dan sorotan IG.',
                        'status'      => 'in_progress',
                        'priority'    => 'high',
                        'assigned_to' => $userMap['22010011'] ?? null, // Ratu Balqis
                        'due_date'    => date('Y-m-d', strtotime('+2 days')),
                    ],
                    [
                        'title'       => 'Penyusunan Kalender Konten Edukasi Teknologi Mingguan',
                        'description' => 'Riset topik artikel info-grafis tech tips untuk mahasiswa.',
                        'status'      => 'todo',
                        'priority'    => 'medium',
                        'assigned_to' => $userMap['22010012'] ?? null, // Triyas
                        'due_date'    => date('Y-m-d', strtotime('+8 days')),
                    ],
                ]
            ],
            [
                'title'       => 'Kaderisasi & Pelatihan Kepemimpinan Mahasiswa (LKMM)',
                'description' => 'Program pembinaan soft skills, upgrading internal pengurus, dan workshop leadership.',
                'division_id' => $psdmId,
                'period_year' => $year,
                'period_term' => 1,
                'start_date'  => date('Y-m-d', strtotime('-5 days')),
                'end_date'    => date('Y-m-d', strtotime('+50 days')),
                'status'      => 'active',
                'progress'    => 33,
                'created_by'  => $userMap['22010013'] ?? 1, // Cut Evelyn
                'created_at'  => $now,
                'updated_at'  => $now,
                'tasks'       => [
                    [
                        'title'       => 'Evaluasi Kuesioner Need Assessment Anggota Baru',
                        'description' => 'Menganalisis minat bakat mahasiswa angkatan baru untuk pemetaan divisi.',
                        'status'      => 'done',
                        'priority'    => 'medium',
                        'assigned_to' => $userMap['22010014'] ?? null, // Arsita
                        'due_date'    => date('Y-m-d', strtotime('-1 days')),
                    ],
                    [
                        'title'       => 'Penyusunan Kurikulum & Silabus Materi LKMM Tingkat Dasar',
                        'description' => 'Menyusun materi manajemen waktu, etika organisasi, dan problem solving.',
                        'status'      => 'in_progress',
                        'priority'    => 'critical',
                        'assigned_to' => $userMap['22010013'] ?? null, // Cut Evelyn
                        'due_date'    => date('Y-m-d', strtotime('+4 days')),
                    ],
                    [
                        'title'       => 'Pendataan Peserta & Koordinasi Pemateri Alumni',
                        'description' => 'Menghubungi pembicara seminar leadership dan menyiapkan surat undangan.',
                        'status'      => 'todo',
                        'priority'    => 'high',
                        'assigned_to' => $userMap['22010015'] ?? null, // Dzaky
                        'due_date'    => date('Y-m-d', strtotime('+9 days')),
                    ],
                ]
            ],
        ];

        foreach ($defaultPrograms as $progData) {
            $tasks = $progData['tasks'];
            unset($progData['tasks']);

            // Cek apakah proker dengan judul dan divisi ini sudah ada
            $existing = $this->db->table('hmt_work_programs')
                                 ->where('title', $progData['title'])
                                 ->where('division_id', $progData['division_id'])
                                 ->get()
                                 ->getRowArray();

            if (!$existing) {
                $this->db->table('hmt_work_programs')->insert($progData);
                $progId = $this->db->insertID();
            } else {
                $progId = (int) $existing['id'];
            }

            // Masukkan tasks
            foreach ($tasks as $task) {
                $existingTask = $this->db->table('hmt_tasks')
                                         ->where('work_program_id', $progId)
                                         ->where('title', $task['title'])
                                         ->get()
                                         ->getRowArray();

                if (!$existingTask) {
                    $task['work_program_id'] = $progId;
                    $task['created_by']      = $progData['created_by'];
                    $task['created_at']      = $now;
                    $task['updated_at']      = $now;
                    $this->db->table('hmt_tasks')->insert($task);
                }
            }
        }
    }

    public function down(): void
    {
        // No destructive rollback needed
    }
}
