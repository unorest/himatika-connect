<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $defaultPassword = password_hash('password123', PASSWORD_BCRYPT);

        // Ambil ID divisi yang ada
        $divisions = $this->db->table('hmt_divisions')->get()->getResultArray();
        $divMap = [];
        foreach ($divisions as $d) {
            $divMap[$d['slug']] = $d['id'];
        }

        $stupportId  = $divMap['stupport'] ?? null;
        $pddId       = $divMap['pdd'] ?? null;
        $psdmId      = $divMap['pdsm'] ?? ($divMap['psdm'] ?? null);
        $intellogyId = $divMap['intellogy'] ?? null;

        $members = [
            // BPH (Badan Pengurus Harian)
            [
                'id'          => 1,
                'name'        => 'Rabiah Aladawiyah',
                'nim'         => '22010001',
                'email'       => 'rabiah@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'admin', // Super Admin (Ketua)
                'division_id' => null,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 2,
                'name'        => 'Samuel Binsar Aditya Simamora',
                'nim'         => '22010002',
                'email'       => 'samuel@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'admin', // Super Admin (Wakil Ketua)
                'division_id' => null,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 3,
                'name'        => 'Vina Lestari',
                'nim'         => '22010003',
                'email'       => 'vina@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota (Sekretaris 1)
                'division_id' => null,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 4,
                'name'        => 'Nadin Nadilah',
                'nim'         => '22010004',
                'email'       => 'nadin@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota (Sekretaris 2)
                'division_id' => null,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 5,
                'name'        => 'Dian Avila',
                'nim'         => '22010005',
                'email'       => 'dian@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota (Bendahara)
                'division_id' => null,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // Divisi Stupport
            [
                'id'          => 6,
                'name'        => 'M. Rendy Amril',
                'nim'         => '22010006',
                'email'       => 'rendy@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'kadiv', // Kepala Divisi Stupport
                'division_id' => $stupportId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 7,
                'name'        => 'Hikmah Indriyani',
                'nim'         => '23010007',
                'email'       => 'hikmah@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota Stupport
                'division_id' => $stupportId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 8,
                'name'        => 'M. Naufal Adzim Ramadhan',
                'nim'         => '23010008',
                'email'       => 'naufal@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota Stupport
                'division_id' => $stupportId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 9,
                'name'        => 'Linawati',
                'nim'         => '23010009',
                'email'       => 'linawati@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota Stupport
                'division_id' => $stupportId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // Divisi PDD
            [
                'id'          => 10,
                'name'        => 'Intan Puji Hirdian',
                'nim'         => '22010010',
                'email'       => 'intan@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'kadiv', // Kepala Divisi PDD
                'division_id' => $pddId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 11,
                'name'        => 'Ratu Balqis Mantika',
                'nim'         => '23010011',
                'email'       => 'balqis@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota PDD
                'division_id' => $pddId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 12,
                'name'        => 'Triyas Apritantina',
                'nim'         => '23010012',
                'email'       => 'triyas@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota PDD
                'division_id' => $pddId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // Divisi PSDM
            [
                'id'          => 13,
                'name'        => 'Cut Evelyn Anwar',
                'nim'         => '22010013',
                'email'       => 'evelyn@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'kadiv', // Kepala Divisi PSDM
                'division_id' => $psdmId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 14,
                'name'        => 'Arsita Rahmawati Dewi',
                'nim'         => '23010014',
                'email'       => 'arsita@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota PSDM
                'division_id' => $psdmId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 15,
                'name'        => 'M. Dzaky Ghaly Ramdhani',
                'nim'         => '23010015',
                'email'       => 'dzaky@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota PSDM
                'division_id' => $psdmId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 16,
                'name'        => 'Johan Sudanta Hla',
                'nim'         => '23010016',
                'email'       => 'johan@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota PSDM
                'division_id' => $psdmId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 17,
                'name'        => 'Risma Diah Wulandari',
                'nim'         => '23010017',
                'email'       => 'risma@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota PSDM
                'division_id' => $psdmId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // Divisi Intellogy
            [
                'id'          => 18,
                'name'        => 'Raden Dimas Taufik Rahmat',
                'nim'         => '22010018',
                'email'       => 'dimas@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'kadiv', // Kepala Divisi Intellogy
                'division_id' => $intellogyId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 19,
                'name'        => 'Fajar Mauludin',
                'nim'         => '23010019',
                'email'       => 'fajar@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota Intellogy
                'division_id' => $intellogyId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 20,
                'name'        => 'Mochammad Wahyu R',
                'nim'         => '23010020',
                'email'       => 'wahyu@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota Intellogy
                'division_id' => $intellogyId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 21,
                'name'        => 'Chandra Handika',
                'nim'         => '23010021',
                'email'       => 'chandra@himatika.org',
                'password'    => $defaultPassword,
                'role'        => 'staff', // Anggota Intellogy
                'division_id' => $intellogyId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        $this->db->disableForeignKeyChecks();
        $this->db->table('hmt_users')->truncate();
        $this->db->table('hmt_users')->insertBatch($members);

        // Update head_user_id di hmt_divisions
        if ($stupportId) {
            $this->db->table('hmt_divisions')->where('id', $stupportId)->update(['head_user_id' => 6]);
        }
        if ($pddId) {
            $this->db->table('hmt_divisions')->where('id', $pddId)->update(['head_user_id' => 10]);
        }
        if ($psdmId) {
            $this->db->table('hmt_divisions')->where('id', $psdmId)->update(['head_user_id' => 13]);
        }
        if ($intellogyId) {
            $this->db->table('hmt_divisions')->where('id', $intellogyId)->update(['head_user_id' => 18]);
        }

        $this->db->enableForeignKeyChecks();

        echo "✅ UserSeeder: 21 anggota & pimpinan resmi HIMATIKA berhasil ditambahkan.\n";
    }
}
