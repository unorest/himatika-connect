<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $defaultPassword = password_hash('password123', PASSWORD_BCRYPT);

        // Ambil divisi yang sudah ada untuk mapping id
        $divisions = $this->db->table('hmt_divisions')->get()->getResultArray();
        $divMap = [];
        foreach ($divisions as $d) {
            $divMap[$d['slug']] = $d['id'];
        }

        $stupportId  = $divMap['stupport'] ?? null;
        $pddId       = $divMap['pdd'] ?? null;
        $pdsmId      = $divMap['pdsm'] ?? null;
        $intellogyId = $divMap['intellogy'] ?? null;

        $users = [
            // Admin / Ketua Umum (BPH)
            [
                'name'        => 'Admin HIMATIKA (Ketua Umum)',
                'nim'         => '22010001',
                'email'       => 'admin@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'admin',
                'division_id' => null, // BPH / Lintas Divisi
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Kadiv Stupport
            [
                'name'        => 'Siti Rahma (Kadiv Stupport)',
                'nim'         => '22010002',
                'email'       => 'stupport@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'kadiv',
                'division_id' => $stupportId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Kadiv PDD
            [
                'name'        => 'Budi Santoso (Kadiv PDD)',
                'nim'         => '22010003',
                'email'       => 'pdd@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'kadiv',
                'division_id' => $pddId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Kadiv PDSM
            [
                'name'        => 'Ahmad Fauzan (Kadiv PDSM)',
                'nim'         => '22010004',
                'email'       => 'pdsm@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'kadiv',
                'division_id' => $pdsmId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Kadiv Intellogy
            [
                'name'        => 'Devina Putri (Kadiv Intellogy)',
                'nim'         => '22010005',
                'email'       => 'intellogy@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'kadiv',
                'division_id' => $intellogyId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Staff Stupport
            [
                'name'        => 'Rian Pratama (Staff Stupport)',
                'nim'         => '23010010',
                'email'       => 'staff.stupport@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'staff',
                'division_id' => $stupportId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Staff PDD
            [
                'name'        => 'Nadia Putri (Staff PDD)',
                'nim'         => '23010011',
                'email'       => 'staff.pdd@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'staff',
                'division_id' => $pddId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Staff Intellogy
            [
                'name'        => 'Farhan Malik (Staff Intellogy)',
                'nim'         => '23010012',
                'email'       => 'staff.intellogy@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'staff',
                'division_id' => $intellogyId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        $this->db->disableForeignKeyChecks();
        $this->db->table('hmt_users')->truncate();
        $this->db->table('hmt_users')->insertBatch($users);

        // Update head_user_id di hmt_divisions
        $usersCreated = $this->db->table('hmt_users')->get()->getResultArray();
        $userMapByEmail = [];
        foreach ($usersCreated as $u) {
            $userMapByEmail[$u['email']] = $u['id'];
        }

        if (isset($userMapByEmail['stupport@himatika.org'], $divMap['stupport'])) {
            $this->db->table('hmt_divisions')->where('id', $divMap['stupport'])->update(['head_user_id' => $userMapByEmail['stupport@himatika.org']]);
        }
        if (isset($userMapByEmail['pdd@himatika.org'], $divMap['pdd'])) {
            $this->db->table('hmt_divisions')->where('id', $divMap['pdd'])->update(['head_user_id' => $userMapByEmail['pdd@himatika.org']]);
        }
        if (isset($userMapByEmail['pdsm@himatika.org'], $divMap['pdsm'])) {
            $this->db->table('hmt_divisions')->where('id', $divMap['pdsm'])->update(['head_user_id' => $userMapByEmail['pdsm@himatika.org']]);
        }
        if (isset($userMapByEmail['intellogy@himatika.org'], $divMap['intellogy'])) {
            $this->db->table('hmt_divisions')->where('id', $divMap['intellogy'])->update(['head_user_id' => $userMapByEmail['intellogy@himatika.org']]);
        }

        $this->db->enableForeignKeyChecks();

        echo "✅ UserSeeder: " . count($users) . " akun pengurus berhasil ditambahkan.\n";
    }
}
