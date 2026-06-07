<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Fakultas;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakultas = [
            ['nama_fakultas' => 'Sains & Teknologi'],
            ['nama_fakultas' => 'Ilmu Sosial'],
        ];

        foreach ($fakultas as $fak) {
            Fakultas::firstOrCreate(['nama_fakultas' => $fak['nama_fakultas']], $fak);
        }
    }
}
