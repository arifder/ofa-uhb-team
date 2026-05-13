<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Notulensi;

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

        elseif (in_array($user->role, ['admin_kas_fst', 'admin_kas_fis'])) {
            $data['totalDosenFakultas'] = Dosen::whereHas('prodi', function($q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id);
            })->count();
            $data['totalKasFakultas']   = 0;
            $data['tagihanBelumLunas']  = 0;
            $data['namaFakultas']       = optional($user->fakultas)->nama_fakultas ?? '-';
        }

        elseif (in_array($user->role, ['admin_notulensi_fst', 'admin_notulensi_fis'])) {
            $data['totalNotulensibulan'] = Notulensi::where('fakultas_id', $user->fakultas_id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count();
            $data['totalRapatTahun'] = Notulensi::where('fakultas_id', $user->fakultas_id)
                ->whereYear('tanggal', now()->year)
                ->count();
            $data['notulensiTerakhir'] = Notulensi::where('fakultas_id', $user->fakultas_id)
                ->latest('tanggal')->first();
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
