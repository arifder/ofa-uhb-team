<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Notulensi;
use App\Models\KasTransaction;
use App\Models\KasTagihan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = ['user' => $user];

        if ($user->role === 'super_admin') {
            $data['totalDosen']     = Dosen::count();
            $data['totalUsers']     = User::count();
            $data['totalFakultas']  = Fakultas::count();
            $data['totalKas']       = 0;
            $data['totalNotulensi'] = Notulensi::count();
            $data['recentUsers']    = User::with('fakultas')
                ->latest()->take(5)->get();
        }

        // Khusus Admin Fakultas (FST / FIS)
        elseif (in_array($user->role, ['admin_fst', 'admin_fis'])) {
            // Data Kas
            $data['total_kas_masuk'] = KasTransaction::where('fakultas_id', $user->fakultas_id)
                                          ->where('jenis', 'masuk')->sum('jumlah');
            $data['total_kas_keluar'] = KasTransaction::where('fakultas_id', $user->fakultas_id)
                                           ->where('jenis', 'keluar')->sum('jumlah');
            $data['saldo_kas'] = $data['total_kas_masuk'] - $data['total_kas_keluar'];
            $data['kas_recent'] = KasTransaction::where('fakultas_id', $user->fakultas_id)
                                      ->with('fakultas')->latest()->take(5)->get();
            $data['tagihan_pending'] = KasTagihan::where('fakultas_id', $user->fakultas_id)
                                        ->where('status', 'belum_lunas')->count();

            // Data Notulensi
            $data['total_notulensi'] = Notulensi::where('fakultas_id', $user->fakultas_id)->count();
            $data['notulensi_recent'] = Notulensi::where('fakultas_id', $user->fakultas_id)
                                        ->latest('tanggal')->take(5)->get();
            $data['namaFakultas'] = optional($user->fakultas)->nama_fakultas ?? '-';
        }

        elseif ($user->role === 'kepala_unit') {
            $data['totalKasMasuk']  = 0;
            $data['totalKasKeluar'] = 0;
            $data['totalRapat']     = 0;
            $data['totalDosen']     = Dosen::count();
        }

        elseif ($user->role === 'dosen') {
            $data['dosenProfile']   = Dosen::where('user_id', $user->id)
                ->with(['prodi', 'prodi.fakultas'])
                ->first();
            $data['tabungan']       = 0;
            $data['uangSosial']     = 0;
            $data['statusBulanIni'] = 'Belum Lunas';
        }

        return view('dashboard', $data);
    }
}
