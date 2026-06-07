<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Dr. Siti Rahma', 'nidn' => '0011223344', 'fakultas' => 'Sains & Teknologi', 'prodi' => 'S1 Informatika'],
            ['nama' => 'Prof. Budi Santoso', 'nidn' => '0022334455', 'fakultas' => 'Sains & Teknologi', 'prodi' => 'S1 Sistem Informasi'],
            ['nama' => 'Dr. Anita Wulandari', 'nidn' => '0033445566', 'fakultas' => 'Ilmu Sosial', 'prodi' => 'S1 Manajemen'],
            ['nama' => 'Drs. Hendra Wijaya', 'nidn' => '0044556677', 'fakultas' => 'Ilmu Sosial', 'prodi' => 'S1 Akuntansi'],
            ['nama' => 'Dr. Maya Putri', 'nidn' => '0055667788', 'fakultas' => 'Sains & Teknologi', 'prodi' => 'S1 Informatika'],
        ];

        foreach ($data as $d) {
            $fakultas = Fakultas::where('nama_fakultas', $d['fakultas'])->first();
            $prodi = Prodi::where('nama_prodi', $d['prodi'])->first();

            if ($fakultas && $prodi) {
                $user = User::firstOrCreate(
                    ['username' => $d['nidn']],
                    [
                        'name' => $d['nama'],
                        'email' => $d['nidn'] . '@ofa-uhb.com',
                        'password' => Hash::make('password'),
                        'role' => 'dosen',
                        'fakultas_id' => $fakultas->id,
                        'status' => 'aktif',
                    ]
                );

                Dosen::firstOrCreate(
                    ['nidn' => $d['nidn']],
                    [
                        'user_id' => $user->id,
                        'prodi_id' => $prodi->id,
                        'nama_lengkap' => $d['nama'],
                    ]
                );
            }
        }
    }
}
