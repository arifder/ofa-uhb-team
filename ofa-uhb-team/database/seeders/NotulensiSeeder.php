<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notulensi;
use App\Models\PesertaRapat;
use App\Models\Fakultas;
use App\Models\User;

class NotulensiSeeder extends Seeder
{
    public function run(): void
    {
        $fst = Fakultas::where('nama_fakultas', 'like', '%Sains%')->first();
        $fis = Fakultas::where('nama_fakultas', 'like', '%Sosial%')->first();
        $admin = User::where('role', 'super_admin')->first();

        if (!$fst || !$fis || !$admin) {
            $this->command->warn('Seeder dilewati: Pastikan data fakultas dan super_admin sudah ada.');
            return;
        }

        // Notulensi 1
        $n1 = Notulensi::create([
            'judul'         => 'Rapat Rutin Mei 2026',
            'tanggal'       => '2026-05-05',
            'tempat'        => 'Ruang Rapat A',
            'agenda'        => "1. Evaluasi kinerja\n2. Persiapan ujian",
            'tindak_lanjut' => 'Koordinasi jadwal ujian',
            'fakultas_id'   => $fst->id,
            'user_id'       => $admin->id,
        ]);
        PesertaRapat::insert([
            ['notulensi_id' => $n1->id, 'dosen_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['notulensi_id' => $n1->id, 'dosen_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Notulensi 2
        $n2 = Notulensi::create([
            'judul'         => 'Rapat Koordinasi April',
            'tanggal'       => '2026-04-15',
            'tempat'        => 'Ruang Rapat B',
            'agenda'        => "1. Laporan kegiatan\n2. Anggaran",
            'tindak_lanjut' => 'Pengajuan anggaran ke dekanat',
            'fakultas_id'   => $fis->id,
            'user_id'       => $admin->id,
        ]);
        PesertaRapat::insert([
            ['notulensi_id' => $n2->id, 'dosen_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['notulensi_id' => $n2->id, 'dosen_id' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Notulensi 3
        $n3 = Notulensi::create([
            'judul'         => 'Rapat Pleno Fakultas',
            'tanggal'       => '2026-05-10',
            'tempat'        => 'Aula Utama',
            'agenda'        => "1. Evaluasi semester\n2. Program kerja",
            'tindak_lanjut' => 'Susun program kerja semester baru',
            'fakultas_id'   => $fst->id,
            'user_id'       => $admin->id,
        ]);
        PesertaRapat::insert([
            ['notulensi_id' => $n3->id, 'dosen_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['notulensi_id' => $n3->id, 'dosen_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['notulensi_id' => $n3->id, 'dosen_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->command->info('NotulensiSeeder: 3 notulensi berhasil dibuat.');
    }
}
