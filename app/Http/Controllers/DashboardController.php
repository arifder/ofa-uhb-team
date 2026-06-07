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
            $data['totalProdi']     = \App\Models\Prodi::count();
            $data['totalUsers']     = User::count();
            $data['totalFakultas']  = Fakultas::count();
            
            $kasMasuk = \App\Models\KasTransaction::where('jenis', 'masuk')->sum('jumlah');
            $kasKeluar = \App\Models\KasTransaction::where('jenis', 'keluar')->sum('jumlah');
            $data['totalKas']       = $kasMasuk - $kasKeluar;
            
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

            $data['totalDosenFakultas'] = Dosen::whereHas('prodi', function($q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id);
            })->count();
            
            $data['kasMasukFakultas']   = $data['total_kas_masuk'];
            $data['kasKeluarFakultas']  = $data['total_kas_keluar'];
            $data['totalKasFakultas']   = $data['saldo_kas'];
            
            $data['tagihanBelumLunas']  = \App\Models\KasTagihan::where('fakultas_id', $user->fakultas_id)
                ->where('status', 'belum_lunas')
                ->where('bulan', now()->month)
                ->where('tahun', now()->year)
                ->count();

            // Data Notulensi
            $data['total_notulensi'] = Notulensi::where('fakultas_id', $user->fakultas_id)->count();
            $data['notulensi_recent'] = Notulensi::where('fakultas_id', $user->fakultas_id)
                                        ->latest('tanggal')->take(5)->get();
            $data['namaFakultas'] = optional($user->fakultas)->nama_fakultas ?? '-';
        }

        elseif ($user->role === 'kepala_unit') {
            $data['totalKasMasuk']  = \App\Models\KasTransaction::where('jenis', 'masuk')->sum('jumlah');
            $data['totalKasKeluar'] = \App\Models\KasTransaction::where('jenis', 'keluar')->sum('jumlah');
            $data['totalRapat']     = Notulensi::count();
            $data['totalDosen']     = Dosen::count();
        }

        elseif ($user->role === 'dosen') {
            $dosen = Dosen::where('user_id', $user->id)
                ->with(['prodi', 'prodi.fakultas'])
                ->first();
            $data['dosenProfile'] = $dosen;
            
            if ($dosen) {
                $data['tabungan'] = \App\Models\KasTransaction::where('dosen_id', $dosen->id)
                    ->where('jenis', 'masuk')
                    ->sum('tabungan');
                $data['uangSosial'] = \App\Models\KasTransaction::where('dosen_id', $dosen->id)
                    ->where('jenis', 'masuk')
                    ->sum('uang_sosial');

                $tagihanBulanIni = \App\Models\KasTagihan::where('dosen_id', $dosen->id)
                    ->where('bulan', now()->month)
                    ->where('tahun', now()->year)
                    ->first();
                $data['statusBulanIni'] = ($tagihanBulanIni && $tagihanBulanIni->status === 'lunas') ? 'Lunas' : 'Belum Lunas';

                $data['tagihanList'] = \App\Models\KasTagihan::with('fakultas')
                    ->where('dosen_id', $dosen->id)
                    ->latest()
                    ->get();
                    
                $data['riwayatKasList'] = \App\Models\KasTransaction::where('dosen_id', $dosen->id)
                    ->where('jenis', 'masuk')
                    ->latest()
                    ->get();
                    
                $data['notulensiList'] = \App\Models\Notulensi::whereHas('dosens', function($q) use ($dosen) {
                    $q->where('dosens.id', $dosen->id);
                })->latest()->get();

            } else {
                $data['tabungan'] = 0;
                $data['uangSosial'] = 0;
                $data['statusBulanIni'] = 'Belum Lunas';
                $data['tagihanList'] = collect();
                $data['riwayatKasList'] = collect();
                $data['notulensiList'] = collect();
            }
        }

        return view('dashboard', $data);
    }
}
