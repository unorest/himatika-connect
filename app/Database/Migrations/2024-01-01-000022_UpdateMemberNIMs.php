<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateMemberNIMs extends Migration
{
    public function up(): void
    {
        $this->db->disableForeignKeyChecks();

        // Drop UNIQUE constraint on nim so duplicate dummy/placeholder NIMs (e.g. 240401000000) don't violate constraints
        try {
            $this->db->query("ALTER TABLE `hmt_users` DROP INDEX `nim`");
        } catch (\Throwable $e) {
            // Index might not exist or already dropped
        }

        try {
            $this->db->query("ALTER TABLE `hmt_users` ADD INDEX `idx_nim` (`nim`)");
        } catch (\Throwable $e) {
            // Index might already exist
        }

        $divisions = $this->db->table('hmt_divisions')->get()->getResultArray();
        $divMap = [];
        foreach ($divisions as $d) {
            $divMap[$d['slug']] = $d['id'];
        }

        $stupportId  = $divMap['stupport'] ?? 1;
        $pddId       = $divMap['pdd'] ?? 2;
        $psdmId      = $divMap['pdsm'] ?? ($divMap['psdm'] ?? 3);
        $intellogyId = $divMap['intellogy'] ?? 4;

        $members = [
            // BPH (Badan Pengurus Harian)
            [
                'id'          => 1,
                'name'        => 'Rabiah Aladawiyah',
                'nim'         => '240401000000',
                'email'       => 'rabiah@himatika.org',
                'role'        => 'admin',
                'division_id' => null,
            ],
            [
                'id'          => 2,
                'name'        => 'Samuel Binsar Aditya Simamora',
                'nim'         => '240401000000',
                'email'       => 'samuel@himatika.org',
                'role'        => 'admin',
                'division_id' => null,
            ],
            [
                'id'          => 3,
                'name'        => 'Vina Lestari',
                'nim'         => '240401000000',
                'email'       => 'vina@himatika.org',
                'role'        => 'staff',
                'division_id' => null,
            ],
            [
                'id'          => 4,
                'name'        => 'Nadin Nadilah',
                'nim'         => '260401071001',
                'email'       => 'nadin@himatika.org',
                'role'        => 'staff',
                'division_id' => null,
            ],
            [
                'id'          => 5,
                'name'        => 'Dian Avila',
                'nim'         => '250401010377',
                'email'       => 'dian@himatika.org',
                'role'        => 'staff',
                'division_id' => null,
            ],

            // Divisi Stupport
            [
                'id'          => 6,
                'name'        => 'M. Rendy Amril',
                'nim'         => '240401010288',
                'email'       => 'rendy@himatika.org',
                'role'        => 'kadiv',
                'division_id' => $stupportId,
            ],
            [
                'id'          => 7,
                'name'        => 'Hikmah Indriyani',
                'nim'         => '250401010395',
                'email'       => 'hikmah@himatika.org',
                'role'        => 'staff',
                'division_id' => $stupportId,
            ],
            [
                'id'          => 8,
                'name'        => 'M. Naufal Adzim Ramadhan',
                'nim'         => '260401630042',
                'email'       => 'naufal@himatika.org',
                'role'        => 'staff',
                'division_id' => $stupportId,
            ],
            [
                'id'          => 9,
                'name'        => 'Linawati',
                'nim'         => '260401010228',
                'email'       => 'linawati@himatika.org',
                'role'        => 'staff',
                'division_id' => $stupportId,
            ],

            // Divisi PDD
            [
                'id'          => 10,
                'name'        => 'Intan Puji Hirdian',
                'nim'         => '250401010210',
                'email'       => 'intan@himatika.org',
                'role'        => 'kadiv',
                'division_id' => $pddId,
            ],
            [
                'id'          => 11,
                'name'        => 'Ratu Bilqis Mantika',
                'nim'         => '240401020203',
                'email'       => 'balqis@himatika.org',
                'role'        => 'staff',
                'division_id' => $pddId,
            ],
            [
                'id'          => 12,
                'name'        => 'Triyas Apritantina',
                'nim'         => '260401010208',
                'email'       => 'triyas@himatika.org',
                'role'        => 'staff',
                'division_id' => $pddId,
            ],

            // Divisi PSDM
            [
                'id'          => 13,
                'name'        => 'Cut Evelyn Anwar',
                'nim'         => '250401010442',
                'email'       => 'cut.evelyn@himatika.org',
                'role'        => 'kadiv',
                'division_id' => $psdmId,
            ],
            [
                'id'          => 14,
                'name'        => 'Arsita Rahmawati Dewi',
                'nim'         => '240401010318',
                'email'       => 'arsita@himatika.org',
                'role'        => 'staff',
                'division_id' => $psdmId,
            ],
            [
                'id'          => 15,
                'name'        => 'M. Dzaky Ghaly Ramdhani',
                'nim'         => '260401010316',
                'email'       => 'dzaky@himatika.org',
                'role'        => 'staff',
                'division_id' => $psdmId,
            ],
            [
                'id'          => 16,
                'name'        => 'Johan Sudanta Hla',
                'nim'         => '260401630027',
                'email'       => 'johan@himatika.org',
                'role'        => 'staff',
                'division_id' => $psdmId,
            ],
            [
                'id'          => 17,
                'name'        => 'Risma Diah Wulandari',
                'nim'         => '260401010315',
                'email'       => 'risma@himatika.org',
                'role'        => 'staff',
                'division_id' => $psdmId,
            ],

            // Divisi Intellogy
            [
                'id'          => 18,
                'name'        => 'Raden Dimas Taufik Rahmat',
                'nim'         => '250401010460',
                'email'       => 'dimas@himatika.org',
                'role'        => 'kadiv',
                'division_id' => $intellogyId,
            ],
            [
                'id'          => 19,
                'name'        => 'Fajar Mauludin',
                'nim'         => '260401010227',
                'email'       => 'fajar@himatika.org',
                'role'        => 'staff',
                'division_id' => $intellogyId,
            ],
            [
                'id'          => 20,
                'name'        => 'Mochammad Wahyu R',
                'nim'         => '260401630016',
                'email'       => 'wahyu@himatika.org',
                'role'        => 'staff',
                'division_id' => $intellogyId,
            ],
            [
                'id'          => 21,
                'name'        => 'Chandra Handika',
                'nim'         => '260401010340',
                'email'       => 'chandra@himatika.org',
                'role'        => 'staff',
                'division_id' => $intellogyId,
            ],
        ];

        foreach ($members as $m) {
            $check = $this->db->table('hmt_users')
                              ->where('id', $m['id'])
                              ->orWhere('email', $m['email'])
                              ->get()
                              ->getRowArray();

            if ($check) {
                $this->db->table('hmt_users')
                         ->where('id', $check['id'])
                         ->update([
                             'name'        => $m['name'],
                             'nim'         => $m['nim'],
                             'email'       => $m['email'],
                             'role'        => $m['role'],
                             'division_id' => $m['division_id'],
                             'updated_at'  => date('Y-m-d H:i:s'),
                         ]);
            } else {
                $m['password']   = password_hash('password123', PASSWORD_BCRYPT);
                $m['is_active']  = 1;
                $m['created_at'] = date('Y-m-d H:i:s');
                $m['updated_at'] = date('Y-m-d H:i:s');
                $this->db->table('hmt_users')->insert($m);
            }
        }

        $this->db->enableForeignKeyChecks();
    }

    public function down(): void
    {
        // No destructive rollback needed
    }
}
