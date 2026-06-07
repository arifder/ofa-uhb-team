<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\Dosen;
use App\Models\KasTagihan;
use App\Models\User;
use App\Helpers\NotifikasiHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schedule;

Artisan::command('tagihan:generate {--month=} {--year=}', function () {
    $month = $this->option('month') ? (int)$this->option('month') : (int)date('n');
    $year = $this->option('year') ? (int)$this->option('year') : (int)date('Y');

    $this->info("Generating tagihan for Period: {$month}/{$year}...");

    $admin = User::where('role', 'super_admin')->first();
    $adminId = $admin ? $admin->id : 1;

    $dosens = Dosen::with('prodi')->get();
    $count = 0;

    foreach ($dosens as $dosen) {
        $exists = KasTagihan::where('dosen_id', $dosen->id)
            ->where('bulan', $month)
            ->where('tahun', $year)
            ->exists();

        if (!$exists) {
            $fakultasId = $dosen->prodi->fakultas_id ?? null;
            if (!$fakultasId) {
                $this->warn("Dosen {$dosen->nama_lengkap} has no prodi/fakultas. Skipping.");
                continue;
            }

            $tagihan = KasTagihan::create([
                'dosen_id' => $dosen->id,
                'fakultas_id' => $fakultasId,
                'bulan' => $month,
                'tahun' => $year,
                'jumlah' => 50000,
                'tanggal_jatuh_tempo' => Carbon::createFromDate($year, $month, 10)->toDateString(),
                'user_id' => $adminId,
                'status' => 'belum_lunas',
                'dibayar_amount' => 0,
            ]);

            $count++;

            if ($dosen->user_id) {
                $namaBulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $namaBulan = $namaBulanList[$month - 1];

                NotifikasiHelper::notifTagihanDibuat(
                    $dosen->user_id,
                    $dosen->nama_lengkap,
                    $namaBulan,
                    $year,
                    '50.000',
                    route('dashboard'),
                    route('kas.tagihan'),
                    $fakultasId
                );
            }
        }
    }

    $this->info("Successfully generated {$count} tagihans.");
})->purpose('Generate monthly tagihan of Rp50.000 for all lecturers');

// Schedule monthly on the 1st
Schedule::command('tagihan:generate')->monthlyOn(1, '00:00');

