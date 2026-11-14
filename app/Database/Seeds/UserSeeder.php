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

        $bphId = $divMap['bph'] ?? 1;
        $akademikId = $divMap['akademik'] ?? 2;
        $humasId = $divMap['humas'] ?? 3;
        $advokasiId = $divMap['advokasi'] ?? 4;
        $teknologiId = $divMap['teknologi'] ?? 7;

        $users = [
            // Admin / Ketua Umum (BPH)
            [
                'name'        => 'Admin HIMATIKA (Ketua Umum)',
                'nim'         => '22010001',
                'email'       => 'admin@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'admin',
                'division_id' => $bphId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Kadiv Akademik
            [
                'name'        => 'Ahmad Fauzan (Kadiv Akademik)',
                'nim'         => '22010002',
                'email'       => 'akademik@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'kadiv',
                'division_id' => $akademikId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Kadiv Advokasi
            [
                'name'        => 'Siti Rahma (Kadiv Advokasi)',
                'nim'         => '22010003',
                'email'       => 'advokasi@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'kadiv',
                'division_id' => $advokasiId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Kadiv Humas
            [
                'name'        => 'Budi Santoso (Kadiv Humas)',
                'nim'         => '22010004',
                'email'       => 'humas@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'kadiv',
                'division_id' => $humasId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Kadiv Teknologi
            [
                'name'        => 'Devina Putri (Kadiv Teknologi)',
                'nim'         => '22010005',
                'email'       => 'teknologi@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'kadiv',
                'division_id' => $teknologiId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Staff Advokasi
            [
                'name'        => 'Rian Pratama (Staff Advokasi)',
                'nim'         => '23010010',
                'email'       => 'rian.staff@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'staff',
                'division_id' => $advokasiId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Staff Teknologi
            [
                'name'        => 'Farhan Malik (Staff Teknologi)',
                'nim'         => '23010011',
                'email'       => 'farhan.staff@himatika.org',
                'password'    => $defaultPassword,
                'avatar'      => null,
                'role'        => 'staff',
                'division_id' => $teknologiId,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        $this->db->table('hmt_users')->insertBatch($users);

        // Update head_user_id di hmt_divisions
        $usersCreated = $this->db->table('hmt_users')->get()->getResultArray();
        $userMapByEmail = [];
        foreach ($usersCreated as $u) {
            $userMapByEmail[$u['email']] = $u['id'];
        }

        if (isset($userMapByEmail['admin@himatika.org'], $divMap['bph'])) {
            $this->db->table('hmt_divisions')->where('id', $divMap['bph'])->update(['head_user_id' => $userMapByEmail['admin@himatika.org']]);
        }
        if (isset($userMapByEmail['akademik@himatika.org'], $divMap['akademik'])) {
            $this->db->table('hmt_divisions')->where('id', $divMap['akademik'])->update(['head_user_id' => $userMapByEmail['akademik@himatika.org']]);
        }
        if (isset($userMapByEmail['advokasi@himatika.org'], $divMap['advokasi'])) {
            $this->db->table('hmt_divisions')->where('id', $divMap['advokasi'])->update(['head_user_id' => $userMapByEmail['advokasi@himatika.org']]);
        }
        if (isset($userMapByEmail['humas@himatika.org'], $divMap['humas'])) {
            $this->db->table('hmt_divisions')->where('id', $divMap['humas'])->update(['head_user_id' => $userMapByEmail['humas@himatika.org']]);
        }
        if (isset($userMapByEmail['teknologi@himatika.org'], $divMap['teknologi'])) {
            $this->db->table('hmt_divisions')->where('id', $divMap['teknologi'])->update(['head_user_id' => $userMapByEmail['teknologi@himatika.org']]);
        }

        echo "✅ UserSeeder: " . count($users) . " pengguna contoh berhasil ditambahkan.\n";
    }
}
