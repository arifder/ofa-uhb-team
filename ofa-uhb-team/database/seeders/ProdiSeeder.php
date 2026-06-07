<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $fakST = Fakultas::where('nama_fakultas', 'Sains & Teknologi')->first();
        $fakIS = Fakultas::where('nama_fakultas', 'Ilmu Sosial')->first();

        if ($fakST) {
            Prodi::firstOrCreate(['nama_prodi' => 'S1 Informatika'], ['nama_prodi' => 'S1 Informatika', 'fakultas_id' => $fakST->id]);
            Prodi::firstOrCreate(['nama_prodi' => 'S1 Sistem Informasi'], ['nama_prodi' => 'S1 Sistem Informasi', 'fakultas_id' => $fakST->id]);
        }

        if ($fakIS) {
            Prodi::firstOrCreate(['nama_prodi' => 'S1 Manajemen'], ['nama_prodi' => 'S1 Manajemen', 'fakultas_id' => $fakIS->id]);
            Prodi::firstOrCreate(['nama_prodi' => 'S1 Akuntansi'], ['nama_prodi' => 'S1 Akuntansi', 'fakultas_id' => $fakIS->id]);
        }
    }
}
