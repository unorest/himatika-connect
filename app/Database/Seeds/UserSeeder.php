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

        // Drop UNIQUE constraint on nim if present to allow duplicate dummy NIMs
        try {
            $this->db->query("ALTER TABLE `hmt_users` DROP INDEX `nim`");
        } catch (\Throwable $e) {}

        try {
            $this->db->query("ALTER TABLE `hmt_users` ADD INDEX `idx_nim` (`nim`)");
        } catch (\Throwable $e) {}

        $members = [
            // BPH (Badan Pengurus Harian)
            [
                'id'          => 1,
                'name'        => 'Rabiah Aladawiyah',
                'nim'         => '240401000000',
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
                'nim'         => '240401000000',
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
                'nim'         => '240401000000',
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
                'nim'         => '260401071001',
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
                'nim'         => '250401010377',
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
                'nim'         => '240401010288',
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
                'nim'         => '250401010395',
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
                'nim'         => '260401630042',
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
                'nim'         => '260401010228',
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
                'nim'         => '250401010210',
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
                'name'        => 'Ratu Bilqis Mantika',
                'nim'         => '240401020203',
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
                'nim'         => '260401010208',
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
                'nim'         => '250401010442',
                'email'       => 'cut.evelyn@himatika.org',
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
                'nim'         => '240401010318',
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
                'nim'         => '260401010316',
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
                'nim'         => '260401630027',
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
                'nim'         => '260401010315',
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
                'nim'         => '250401010460',
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
                'nim'         => '260401010227',
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
                'nim'         => '260401630016',
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
                'nim'         => '260401010340',
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

        foreach ($members as $member) {
            $existing = $this->db->table('hmt_users')
                                 ->where('id', $member['id'])
                                 ->orWhere('email', $member['email'])
                                 ->get()
                                 ->getRowArray();

            if ($existing) {
                $this->db->table('hmt_users')->where('id', $existing['id'])->update($member);
            } else {
                $this->db->table('hmt_users')->insert($member);
            }
        }

        // Sinkronisasi Kepala Divisi (head_user_id)
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

        echo "✅ UserSeeder: 21 anggota & pengurus HIMATIKA berhasil diperbarui.\n";
    }
}
