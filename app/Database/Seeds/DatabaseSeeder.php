<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('DivisionSeeder');
        $this->call('CategorySeeder');
        $this->call('UserSeeder');
        $this->call('SampleDataSeeder');

        echo "\n🎉 Semua Seeder Berhasil Dijalankan!\n";
    }
}
