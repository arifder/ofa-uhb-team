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

            // Admin Kas FST
            [
                'name' => 'Admin Kas FST',
                'username' => 'adminkasfst',
                'email' => 'adminkasfst@ofa.com',
                'password' => Hash::make('adminfst123'),
                'role' => 'admin_kas_fst',
                'fakultas_id' => $fakultasST ? $fakultasST->id : null,
                'status' => 'aktif',
            ],

            // Admin Kas FIS
            [
                'name' => 'Admin Kas FIS',
                'username' => 'adminkasfis',
                'email' => 'adminkasfis@ofa.com',
                'password' => Hash::make('adminkasfis123'),
                'role' => 'admin_kas_fis',
                'fakultas_id' => $fakultasIS ? $fakultasIS->id : null,
                'status' => 'aktif',
            ],

            // Admin Notulensi FST
            [
                'name' => 'Admin Notulensi FST',
                'username' => 'adminnotfst',
                'email' => 'adminnotfst@ofa.com',
                'password' => Hash::make('adminnotfst123'),
                'role' => 'admin_notulensi_fst',
                'fakultas_id' => $fakultasST ? $fakultasST->id : null,
                'status' => 'aktif',
            ],

            // Admin Notulensi FIS
            [
                'name' => 'Admin Notulensi FIS',
                'username' => 'adminnotfis',
                'email' => 'adminnotfis@ofa.com',
                'password' => Hash::make('adminnotfis123'),
                'role' => 'admin_notulensi_fis',
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
            User::firstOrCreate(['username' => $user['username']], $user);
        }
    }
}
