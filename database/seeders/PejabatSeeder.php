<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PejabatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates sample users with jabatan_struktural for export BAP fitur:
     * - BAAK (Universitas)
     * - Dekan (per fakultas)
     * - Kaprodi (per prodi)
     * - Kemahasiswaan
     * - LPPM
     */
    public function run(): void
    {
        // 1. BAAK (Universitas level — tanpa fakultas_id/prodi_id)
        $this->createUserIfNotExists(
            'Dr. Ahmad Fauzi, M.Pd',
            'baak',
            'baak@uhb.ac.id',
            'BaAk2024',
            'dosen',
            'BAAK'
        );

        // 2. Kemahasiswaan
        $this->createUserIfNotExists(
            'Ir. Budi Santoso, M.T.',
            'kemahasiswaan',
            'kemahasiswaan@uhb.ac.id',
            'Kemah2024',
            'dosen',
            'Kemahasiswaan'
        );

        // 3. LPPM
        $this->createUserIfNotExists(
            'Prof. Dr. Siti Rahayu, M.Sc.',
            'lppm',
            'lppm@uhb.ac.id',
            'Lppm2024',
            'dosen',
            'LPPM'
        );

        // 4. Dekan — one per fakultas
        $fakultas = \App\Models\Fakultas::all();
        foreach ($fakultas as $fak) {
            $jabatan = 'Dekan';
            // Cek apakah sudah ada user dengan jabatan Dekan di fakultas ini
            $exists = \App\Models\User::where('fakultas_id', $fak->id)
                ->where('jabatan_struktural', 'Dekan')
                ->exists();

            if (!$exists) {
                // Auto-generate nama dekan untuk tabel fakultas
                $namaDekan = 'Prof. Dr. ' . str_replace(' ', '', ucwords(str_replace('-', ' ', strtolower($fak->nama_fakultas)))) . ', M.Pd.';

                DB::table('users')->updateOrInsert(
                    [
                        'username' => 'dekan_fakultas_' . $fak->id,
                    ],
                    [
                        'name' => $namaDekan,
                        'email' => 'dekan.' . strtolower(str_replace(' ', '.', $fak->nama_fakultas)) . '@uhb.ac.id',
                        'password' => Hash::make('Dekan2024'),
                        'role' => 'dosen',
                        'fakultas_id' => $fak->id,
                        'prodi_id' => null,
                        'jabatan_struktural' => $jabatan,
                        'status' => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Isi juga nama_dekan di tabel fakultas
                if (!$fak->nama_dekan) {
                    $fak->update(['nama_dekan' => $namaDekan]);
                }
            }
        }

        // 5. Kaprodi — one per prodi
        $prodis = \App\Models\Prodi::all();
        foreach ($prodis as $prodi) {
            $exists = \App\Models\User::where('prodi_id', $prodi->id)
                ->where('jabatan_struktural', 'Kaprodi')
                ->exists();

            if (!$exists) {
                $namaKaprodi = 'Dr. ' . str_replace(' ', '', ucwords(str_replace('-', ' ', strtolower($prodi->nama_prodi)))) . ', M.T.';

                DB::table('users')->updateOrInsert(
                    [
                        'username' => 'kaprodi_prodi_' . $prodi->id,
                    ],
                    [
                        'name' => $namaKaprodi,
                        'email' => 'kaprodi.' . strtolower(str_replace(' ', '.', $prodi->nama_prodi)) . '@uhb.ac.id',
                        'password' => Hash::make('Kaprodi2024'),
                        'role' => 'dosen',
                        'fakultas_id' => $prodi->fakultas_id,
                        'prodi_id' => $prodi->id,
                        'jabatan_struktural' => 'Kaprodi',
                        'status' => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Isi juga nama_kaprodi di tabel prodi
                if (!$prodi->nama_kaprodi) {
                    $prodi->update(['nama_kaprodi' => $namaKaprodi]);
                }
            }
        }

        $this->command->info('Pejabat users created successfully.');
    }

    /**
     * Create a user if not already exists with given username.
     */
    protected function createUserIfNotExists(string $name, string $username, string $email, string $password, string $role, string $jabatan): void
    {
        $exists = \App\Models\User::where('username', $username)->exists();

        if ($exists) {
            $this->command->info("User '{$username}' already exists, skipping.");
            return;
        }

        \App\Models\User::create([
            'name'               => $name,
            'username'           => $username,
            'email'              => $email,
            'password'           => Hash::make($password),
            'role'               => $role,
            'fakultas_id'        => null,
            'prodi_id'           => null,
            'jabatan_struktural' => $jabatan,
            'status'             => 'aktif',
        ]);

        $this->command->info("User '{$username}' created with jabatan_struktural = '{$jabatan}'.");
    }
}

