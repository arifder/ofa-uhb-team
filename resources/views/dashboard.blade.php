@extends('layouts.dashboard')
@section('title', 'Dashboard')

@push('styles')
<style>
  .banner {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 13px;
    border: 0.5px solid;
  }
  .banner i { font-size: 16px; flex-shrink: 0; }
  .banner-blue  { background:#f0f9ff; border-color:#bae6fd; color:#0369a1; }
  .banner-teal  { background:#f0fdfa; border-color:#ccfbf1; color:#0f766e; }
  .banner-green { background:#f0fdf4; border-color:#bbf7d0; color:#166534; }
</style>
@endpush

@section('content')
@php $user = auth()->user(); @endphp

{{-- ============================================================ --}}
{{-- SUPER ADMIN                                                   --}}
{{-- ============================================================ --}}
@if($user->role === 'super_admin')

  <div class="role-tabs">
    <div class="rtab active">Super Admin</div>
    <div class="rtab">Admin Kas</div>
    <div class="rtab">Admin Notulensi</div>
    <div class="rtab">Kepala Unit</div>
    <div class="rtab">Dosen</div>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label"><i class="ti ti-users" style="font-size:13px"></i> Total Dosen</div>
      <div class="stat-val">{{ $totalDosen }}</div>
      <div class="stat-sub" style="color:#9ca3af">Terdaftar di sistem</div>
    </div>
    <div class="stat-card">
      <div class="stat-label"><i class="ti ti-users-group" style="font-size:13px"></i> Total User</div>
      <div class="stat-val">{{ $totalUsers }}</div>
      <div class="stat-sub" style="color:#9ca3af">Semua role</div>
    </div>
    <div class="stat-card">
      <div class="stat-label"><i class="ti ti-school" style="font-size:13px"></i> Total Fakultas</div>
      <div class="stat-val">{{ $totalFakultas }}</div>
      <div class="stat-sub" style="color:#9ca3af">Aktif</div>
    </div>
  </div>

  <p class="section-title"><i class="ti ti-layout-grid"></i> Modul Utama</p>
  <div class="modules-grid">
    <div class="mod-card">
      <div class="mod-icon mod-blue"><i class="ti ti-cash-register"></i></div>
      <div class="mod-title">Manajemen Kas</div>
      <div class="mod-desc">Kelola kas masuk, kas keluar, dan tagihan dosen seluruh fakultas.</div>
      <div class="mod-footer">
        <i class="ti ti-circle-check" style="color:#10B981;font-size:13px"></i>
        {{ $totalDosen }} dosen aktif &nbsp;·&nbsp;
        <a href="#" style="color:#1d4ed8">Buka →</a>
      </div>
    </div>
    <div class="mod-card">
      <div class="mod-icon mod-teal"><i class="ti ti-clipboard-list"></i></div>
      <div class="mod-title">Notulensi Rapat</div>
      <div class="mod-desc">Catat hasil rapat, kelola peserta, dan export Berita Acara (BAP).</div>
      <div class="mod-footer">
        <i class="ti ti-circle-check" style="color:#10B981;font-size:13px"></i>
        {{ $totalNotulensi }} notulensi &nbsp;·&nbsp;
        <a href="{{ route('notulensi.index') }}" style="color:#0f766e">Buka →</a>
      </div>
    </div>
  </div>

  <p class="section-title"><i class="ti ti-activity"></i> User Terbaru</p>
  <div class="activity-list">
    <div class="activity-header">Log Registrasi <span class="view-all">Lihat semua</span></div>
    @forelse($recentUsers as $ru)
    <div class="activity-item">
      <div class="act-dot act-blue"></div>
      <div class="act-text">
        <b>{{ $ru->name }}</b> —
        <span style="color:#64748b">{{ $ru->role_label }}</span>
        {{ $ru->fakultas ? '· '.$ru->fakultas->nama_fakultas : '' }}
      </div>
      <div class="act-time">{{ $ru->created_at->diffForHumans() }}</div>
    </div>
    @empty
    <div class="activity-item">
      <div class="act-text" style="color:#9ca3af">Belum ada user terdaftar.</div>
    </div>
    @endforelse
  </div>

{{-- ============================================================ --}}
{{-- ADMIN FAKULTAS (FST / FIS)                                   --}}
{{-- ============================================================ --}}
@elseif(in_array($user->role, ['admin_fst', 'admin_fis']))

  <div class="banner banner-blue">
    <i class="ti ti-shield-check"></i>
    <span><b>{{ $user->role_label }}</b> — {{ $namaFakultas }}</span>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Saldo Kas Terkini</div>
      <div class="stat-val">Rp {{ number_format($saldo_kas ?? 0, 0, ',', '.') }}</div>
      <div class="stat-sub">Masuk: Rp {{ number_format($total_kas_masuk ?? 0, 0, ',', '.') }} | Keluar: Rp {{ number_format($total_kas_keluar ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Tagihan Belum Lunas</div>
      <div class="stat-val">{{ $tagihan_pending ?? 0 }}</div>
      <div class="stat-sub">dosen bulan ini</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Notulensi Rapat</div>
      <div class="stat-val">{{ $total_notulensi ?? 0 }}</div>
      <div class="stat-sub">seluruh rapat fakultas</div>
    </div>
  </div>

  <p class="section-title"><i class="ti ti-layout-grid"></i> Modul Aktif</p>
  <div class="modules-grid">
    <div class="mod-card">
      <div class="mod-icon mod-blue"><i class="ti ti-cash-register"></i></div>
      <div class="mod-title">Manajemen Kas</div>
      <div class="mod-desc">Kelola kas masuk, kas keluar, dan tagihan dosen {{ $namaFakultas }}.</div>
      <div class="mod-footer"><a href="{{ route('kas.masuk') }}" style="color:#1d4ed8">Buka →</a></div>
    </div>
    <div class="mod-card">
      <div class="mod-icon mod-teal"><i class="ti ti-clipboard-list"></i></div>
      <div class="mod-title">Notulensi Rapat</div>
      <div class="mod-desc">Kelola notulensi rapat, peserta, dan BAP untuk {{ $namaFakultas }}.</div>
      <div class="mod-footer"><a href="{{ route('notulensi.index') }}" style="color:#0f766e">Buka →</a></div>
    </div>
  </div>

{{-- ============================================================ --}}
{{-- KEPALA UNIT                                                   --}}
{{-- ============================================================ --}}
@elseif($user->role === 'kepala_unit')

  <div class="banner banner-green">
    <i class="ti ti-eye"></i>
    <span><b>Mode Hanya Lihat</b> — Anda tidak dapat menambah, mengubah, atau menghapus data.</span>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Kas Masuk</div>
      <div class="stat-val">Rp 0</div>
      <div class="stat-sub">seluruh fakultas</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Kas Keluar</div>
      <div class="stat-val">Rp 0</div>
      <div class="stat-sub">YTD {{ now()->year }}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Dosen</div>
      <div class="stat-val">{{ $totalDosen }}</div>
      <div class="stat-sub">semua fakultas</div>
    </div>
  </div>

  <div class="modules-grid">
    <div class="mod-card" style="opacity:0.8">
      <div class="mod-icon mod-blue"><i class="ti ti-report-analytics"></i></div>
      <div class="mod-title">Riwayat Kas</div>
      <div class="mod-desc">Lihat seluruh riwayat kas masuk dan keluar semua fakultas.</div>
      <div class="mod-footer"><i class="ti ti-eye" style="font-size:13px"></i> Hanya lihat</div>
    </div>
    <div class="mod-card" style="opacity:0.8">
      <div class="mod-icon mod-teal"><i class="ti ti-clipboard-text"></i></div>
      <div class="mod-title">Riwayat Notulensi</div>
      <div class="mod-desc">Lihat seluruh notulensi dan BAP rapat yang telah diarsipkan.</div>
      <div class="mod-footer"><i class="ti ti-eye" style="font-size:13px"></i> Hanya lihat</div>
    </div>
  </div>

{{-- ============================================================ --}}
{{-- DOSEN                                                         --}}
{{-- ============================================================ --}}
@elseif($user->role === 'dosen')

  <div class="banner banner-blue">
    <i class="ti ti-user"></i>
    <span>
      <b>{{ $dosenProfile->nama_lengkap ?? $user->name }}</b>
      @if($dosenProfile && $dosenProfile->prodi)
        — {{ $dosenProfile->prodi->nama_prodi }}
        @if($dosenProfile->prodi->fakultas)
          , {{ $dosenProfile->prodi->fakultas->nama_fakultas }}
        @endif
      @endif
    </span>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Tabungan Saya</div>
      <div class="stat-val">Rp {{ number_format($tabungan, 0, ',', '.') }}</div>
      <div class="stat-sub" style="color:#9ca3af">akan diisi setelah modul kas</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Uang Sosial</div>
      <div class="stat-val">Rp {{ number_format($uangSosial, 0, ',', '.') }}</div>
      <div class="stat-sub" style="color:#9ca3af">akan diisi setelah modul kas</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Status Bulan Ini</div>
      @if($statusBulanIni === 'Lunas')
        <div class="stat-val" style="font-size:16px;color:#059669">✓ Lunas</div>
      @else
        <div class="stat-val" style="font-size:16px;color:#dc2626">✗ Belum Lunas</div>
      @endif
      <div class="stat-sub" style="color:#9ca3af">akan update setelah modul kas</div>
    </div>
  </div>

  <div class="modules-grid">
    <div class="mod-card" style="opacity:0.8">
      <div class="mod-icon mod-blue"><i class="ti ti-history"></i></div>
      <div class="mod-title">Riwayat Kas Saya</div>
      <div class="mod-desc">Lihat histori pembayaran kas (tabungan & sosial) Anda.</div>
      <div class="mod-footer"><i class="ti ti-eye" style="font-size:13px"></i> Hanya lihat</div>
    </div>
    <div class="mod-card" style="opacity:0.8">
      <div class="mod-icon mod-teal"><i class="ti ti-clipboard-text"></i></div>
      <div class="mod-title">Notulensi Rapat</div>
      <div class="mod-desc">Lihat notulensi rapat yang Anda ikuti beserta BAP-nya.</div>
      <div class="mod-footer"><i class="ti ti-eye" style="font-size:13px"></i> Hanya lihat</div>
    </div>
  </div>

@endif

@endsection

@push('scripts')
<script>
  // No tab switching needed — each role has its own dedicated view
</script>
@endpush
