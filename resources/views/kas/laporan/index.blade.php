@extends('layouts.dashboard')
@section('title', 'Laporan Kas')

@push('styles')
<style>
    .master-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 24px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .master-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .master-table th, .master-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .master-table th { background: #f8fafc; font-weight: 600; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: .05em; }
    .master-table tbody tr:hover { background: #f8fafc; }
    .master-table tbody tr:last-child td { border-bottom: none; }

    .stat-card {
        display: inline-block;
        padding: 16px 24px;
        border-radius: 12px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .stat-masuk  { background: linear-gradient(135deg, #d1fae5, #a7f3d0); border: 1px solid #6ee7b7; }
    .stat-keluar { background: linear-gradient(135deg, #fee2e2, #fecaca); border: 1px solid #fca5a5; }
    .stat-saldo  { background: linear-gradient(135deg, #dbeafe, #bfdbfe); border: 1px solid #93c5fd; }
    .stat-number { font-size: 22px; font-weight: 700; display: block; margin-bottom: 2px; }
    .stat-label  { font-size: 12px; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: .05em; }

    .saldo-positif { color: #059669; }
    .saldo-negatif { color: #dc2626; }

    .fak-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .fak-fst  { background: #dbeafe; color: #1d4ed8; }
    .fak-fis  { background: #fef3c7; color: #92400e; }

    .btn-primary { background: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-outline { background: #fff; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; }
    .btn-outline:hover { background: #f1f5f9; }

    .filter-control { border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; font-size: 13px; outline: none; }
    .filter-control:focus { border-color: #2563eb; }
</style>
@endpush

@section('content')

@php $authUser = auth()->user(); @endphp

{{-- Page Header --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:18px; font-weight:600; color:#1e293b;">Laporan Kas</h2>
</div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('kas.laporan') }}" class="master-card p-4 mb-4">
    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <select name="tahun" class="filter-control" style="width:120px;" onchange="this.form.submit()">
            @foreach(range(now()->year, now()->year - 5) as $t)
                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        @if(in_array($authUser->role, ['super_admin', 'kepala_unit']))
        <select name="fakultas_id" class="filter-control" style="width:200px;" onchange="this.form.submit()">
            <option value="">Semua Fakultas</option>
            @foreach($fakultasList as $fak)
                <option value="{{ $fak->id }}" {{ $fakId == $fak->id ? 'selected' : '' }}>
                    {{ $fak->nama_fakultas }}
                </option>
            @endforeach
        </select>
        @endif
        <button type="submit" class="btn-outline">Tampilkan</button>
    </div>
</form>

{{-- Summary Cards --}}
<div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:24px;">
    <div class="stat-card stat-masuk">
        <span class="stat-label">Total Kas Masuk</span>
        <span class="stat-number" style="color:#059669;">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
    </div>
    <div class="stat-card stat-keluar">
        <span class="stat-label">Total Kas Keluar</span>
        <span class="stat-number" style="color:#dc2626;">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
    </div>
    <div class="stat-card stat-saldo">
        <span class="stat-label">Saldo</span>
        <span class="stat-number {{ $saldo >= 0 ? 'saldo-positif' : 'saldo-negatif' }}">
            Rp {{ number_format($saldo, 0, ',', '.') }}
        </span>
    </div>
</div>

{{-- Breakdown Table --}}
<div class="master-card">
    <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0;">
        <h3 style="font-size:14px; font-weight:600; color:#1e293b;">Rekapitulasi Per Bulan — {{ $tahun }}</h3>
    </div>
    <table class="master-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th style="text-align:right;">Kas Masuk</th>
                <th style="text-align:right;">Kas Keluar</th>
                <th style="text-align:right;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php $runningSaldo = 0; @endphp
            @forelse($reakByBulan as $row)
            @php
                $runningSaldo += $row['saldo'];
            @endphp
            <tr>
                <td>
                    <span style="font-weight:500;">{{ $row['bulan_nama'] }}</span>
                    <span style="color:#94a3b8; font-size:11px; margin-left:6px;">{{ $row['bulan_romawi'] }}</span>
                </td>
                <td style="text-align:right; color:#059669; font-weight:600;">
                    @if($row['total_masuk'] > 0)
                    Rp {{ number_format($row['total_masuk'], 0, ',', '.') }}
                    @else
                    <span style="color:#9ca3af;">-</span>
                    @endif
                </td>
                <td style="text-align:right; color:#dc2626; font-weight:600;">
                    @if($row['total_keluar'] > 0)
                    Rp {{ number_format($row['total_keluar'], 0, ',', '.') }}
                    @else
                    <span style="color:#9ca3af;">-</span>
                    @endif
                </td>
                <td style="text-align:right; font-weight:700; color:{{ $runningSaldo >= 0 ? '#059669' : '#dc2626' }};">
                    Rp {{ number_format($runningSaldo, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center; padding:40px; color:#9ca3af;">
                    <i class="ti ti-chart-bar" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                    Tidak ada data untuk tahun ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background:#f8fafc; font-weight:700;">
                <td>TOTAL</td>
                <td style="text-align:right; color:#059669;">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                <td style="text-align:right; color:#dc2626;">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                <td style="text-align:right; color:{{ $saldo >= 0 ? '#059669' : '#dc2626' }};">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>

@endsection