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
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@ofa-uhb.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'fakultas_id' => null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Admin ST',
                'username' => 'adminst',
                'email' => 'adminst@ofa-uhb.com',
                'password' => Hash::make('password'),
                'role' => 'admin_fakultas',
                'fakultas_id' => $fakultasST ? $fakultasST->id : null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Admin IS',
                'username' => 'adminis',
                'email' => 'adminis@ofa-uhb.com',
                'password' => Hash::make('password'),
                'role' => 'admin_fakultas',
                'fakultas_id' => $fakultasIS ? $fakultasIS->id : null,
                'status' => 'aktif',
            ],
            [
                'name' => 'Kepala Unit',
                'username' => 'kepalunit',
                'email' => 'kepalaunit@ofa-uhb.com',
                'password' => Hash::make('password'),
                'role' => 'kepala_unit',
                'fakultas_id' => null,
                'status' => 'aktif',
            ]
        ];

        foreach ($users as $user) {
            User::firstOrCreate(['username' => $user['username']], $user);
        }
    }
}
