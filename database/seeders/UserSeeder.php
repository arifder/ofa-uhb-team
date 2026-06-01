<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Fakultas;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakultasST = Fakultas::where('nama_fakultas', 'Sains & Teknologi')->first();
        $fakultasIS = Fakultas::where('nama_fakultas', 'Ilmu Sosial')->first();

        $users = [
            // Super Admin
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@ofa-uhb.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'fakultas_id' => null,
                'status' => 'aktif',
            ],


            // Admin FST
            [
                'name' => 'Admin FST',
                'username' => 'adminfst',
                'email' => 'adminfst@ofa.com',
                'password' => Hash::make('adminfst123'),
                'role' => 'admin_fst',
                'fakultas_id' => $fakultasST ? $fakultasST->id : null,
                'status' => 'aktif',
            ],

            // Admin FIS
            [
                'name' => 'Admin FIS',
                'username' => 'adminfis',
                'email' => 'adminfis@ofa.com',
                'password' => Hash::make('adminfis123'),
                'role' => 'admin_fis',
                'fakultas_id' => $fakultasIS ? $fakultasIS->id : null,
                'status' => 'aktif',
            ],

            // Kepala Unit
            [
                'name' => 'Kepala Unit',
                'username' => 'kepalunit',
                'email' => 'kepalaunit@ofa.com',
                'password' => Hash::make('kepalunit123'),
                'role' => 'kepala_unit',
                'fakultas_id' => null,
                'status' => 'aktif',
            ],

            // Dosen
            [
                'name' => 'Dr. Siti Rahma',
                'username' => 'drsitirahma',
                'email' => 'drsitirahma@ofa.com',
                'password' => Hash::make('drsitirahma123'),
                'role' => 'dosen',
                'fakultas_id' => $fakultasST ? $fakultasST->id : null,
                'status' => 'aktif',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['username' => $user['username']], $user);
        }
    }
}
