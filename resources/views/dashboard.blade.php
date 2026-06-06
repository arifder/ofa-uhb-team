@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas dan statistik sistem')

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

  /* ── Modal Popup ─────────────────────────────── */
  .custom-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    align-items: center;
    justify-content: center;
    z-index: 999;
    backdrop-filter: blur(3px);
    animation: fadeIn .15s ease;
  }
  .custom-modal.active { display: flex; }
  @keyframes fadeIn { from { opacity: 0 } to { opacity: 1 } }
  .custom-modal-content {
    background: #fff;
    width: 460px;
    max-width: 95vw;
    max-height: 90vh;
    overflow-y: auto;
    border-radius: 16px;
    box-shadow: 0 25px 50px rgba(0,0,0,.18);
    font-family: 'Plus Jakarta Sans', sans-serif;
    animation: slideUp .2s ease;
  }
  @keyframes slideUp { from { transform: translateY(20px); opacity:0 } to { transform: translateY(0); opacity:1 } }
  .custom-modal-header {
    padding: 18px 22px;
    border-bottom: 1px solid #e2e8f0;
    font-weight: 700;
    font-size: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 1;
    border-radius: 16px 16px 0 0;
  }
  .custom-modal-body { padding: 22px; font-size: 13px; }
  .custom-modal-footer {
    padding: 16px 22px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    background: #f8fafc;
    border-radius: 0 0 16px 16px;
    position: sticky;
    bottom: 0;
  }
  .icon-btn {
    border: none;
    background: #f1f5f9;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 16px;
    transition: background .15s;
  }
  .icon-btn:hover { background: #e2e8f0; color: #1e293b; }
</style>
@endpush

@section('content')
@php $user = auth()->user(); @endphp

{{-- ============================================================ --}}
{{-- SUPER ADMIN                                                   --}}
{{-- ============================================================ --}}
@if($user->role === 'super_admin')



  <div class="stats-grid">
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
    <div class="stat-card">
      <div class="stat-label"><i class="ti ti-building" style="font-size:13px"></i> Total Prodi</div>
      <div class="stat-val">{{ $totalProdi }}</div>
      <div class="stat-sub" style="color:#9ca3af">Program studi aktif</div>
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
        <a href="{{ route('kas.masuk') }}" style="color:#1d4ed8">Buka →</a>
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



  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Kas Masuk</div>
      <div class="stat-val">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</div>
      <div class="stat-sub">seluruh fakultas</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Kas Keluar</div>
      <div class="stat-val">Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}</div>
      <div class="stat-sub">YTD {{ now()->year }}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Dosen</div>
      <div class="stat-val">{{ $totalDosen }}</div>
      <div class="stat-sub">semua fakultas</div>
    </div>
  </div>

  <div class="modules-grid">
    <div class="mod-card">
      <div class="mod-icon mod-blue"><i class="ti ti-report-analytics"></i></div>
      <div class="mod-title">Riwayat Kas</div>
      <div class="mod-desc">Lihat seluruh riwayat kas masuk dan keluar semua fakultas.</div>
      <div class="mod-footer">
        <i class="ti ti-eye" style="font-size:13px; color:#1d4ed8"></i>
        <a href="{{ route('kas.masuk') }}" style="color:#1d4ed8; font-weight:500;">Buka Laporan →</a>
      </div>
    </div>
    <div class="mod-card">
      <div class="mod-icon mod-teal"><i class="ti ti-clipboard-text"></i></div>
      <div class="mod-title">Riwayat Notulensi</div>
      <div class="mod-desc">Lihat seluruh notulensi dan BAP rapat yang telah diarsipkan.</div>
      <div class="mod-footer">
        <i class="ti ti-eye" style="font-size:13px; color:#0f766e"></i>
        <a href="{{ route('notulensi.index') }}" style="color:#0f766e; font-weight:500;">Buka Arsip →</a>
      </div>
    </div>
  </div>

{{-- ============================================================ --}}
{{-- DOSEN                                                         --}}
{{-- ============================================================ --}}
@elseif($user->role === 'dosen')



  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Tabungan Saya</div>
      <div class="stat-val">Rp {{ number_format($tabungan, 0, ',', '.') }}</div>
      <div class="stat-sub" style="color:#9ca3af">Total riwayat kas</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Uang Sosial</div>
      <div class="stat-val">Rp {{ number_format($uangSosial, 0, ',', '.') }}</div>
      <div class="stat-sub" style="color:#9ca3af">Total partisipasi</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Status Bulan Ini</div>
      @if($statusBulanIni === 'Lunas')
        <div class="stat-val" style="font-size:16px;color:#059669;font-weight:700;">✓ Lunas</div>
      @else
        <div class="stat-val" style="font-size:16px;color:#dc2626;font-weight:700;">✗ Belum Lunas</div>
      @endif
      <div class="stat-sub" style="color:#9ca3af">{{ now()->translatedFormat('F Y') }}</div>
    </div>
  </div>

  <p class="section-title" style="margin-top:24px;"><i class="ti ti-file-invoice"></i> Tagihan Kas Saya</p>
  <div class="master-card" style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; margin-bottom:24px;">
      <div class="table-responsive">
          <table style="width:100%; border-collapse:collapse; font-size:13px; text-align:left;">
              <thead style="background:#f8fafc; font-size:11px; color:#475569; text-transform:uppercase;">
                  <tr>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Periode</th>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Jumlah</th>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Status</th>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($tagihanList as $tg)
                  @php 
                     $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; 
                     $isLunas = $tg->status === 'lunas';
                  @endphp
                  <tr>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; font-weight:500;">{{ $namaBulan[($tg->bulan)-1] }} {{ $tg->tahun }}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; font-weight:600;">Rp {{ number_format($tg->jumlah, 0, ',', '.') }}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">
                          <span style="padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; {{ $isLunas ? 'background:#d1fae5; color:#065f46;' : 'background:#fee2e2; color:#991b1b;' }}">
                              {{ $isLunas ? 'Lunas' : 'Belum Lunas' }}
                          </span>
                      </td>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">
                          @if(!$isLunas)
                          <button onclick="openBayarModal({{ $tg->id }}, {{ $tg->jumlah }}, {{ $tg->dibayar_amount }}, '{{ $namaBulan[($tg->bulan)-1] }}', {{ $tg->tahun }})" style="background:#059669; color:#fff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; font-size:12px; font-weight:500; display:inline-flex; align-items:center; gap:4px;"><i class="ti ti-cash"></i> Bayar</button>
                          @else
                          <span style="color:#9ca3af; font-size:12px;"><i class="ti ti-check"></i> Selesai</span>
                          @endif
                      </td>
                  </tr>
                  @empty
                  <tr><td colspan="4" style="padding:20px; text-align:center; color:#9ca3af;">Belum ada tagihan.</td></tr>
                  @endforelse
              </tbody>
          </table>
      </div>
  </div>

  <p class="section-title"><i class="ti ti-history"></i> Riwayat Pembayaran Kas</p>
  <div class="master-card" style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; margin-bottom:24px;">
      <div class="table-responsive">
          <table style="width:100%; border-collapse:collapse; font-size:13px; text-align:left;">
              <thead style="background:#f8fafc; font-size:11px; color:#475569; text-transform:uppercase;">
                  <tr>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Tanggal</th>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Total Bayar</th>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Tabungan (33.33%)</th>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Uang Sosial (66.67%)</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($riwayatKasList as $kas)
                  <tr>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">{{ $kas->tanggal->format('d/m/Y') }}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; font-weight:600;">Rp {{ number_format($kas->jumlah, 0, ',', '.') }}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#059669; font-weight:500;">+ Rp {{ number_format($kas->tabungan, 0, ',', '.') }}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#2563eb; font-weight:500;">+ Rp {{ number_format($kas->uang_sosial, 0, ',', '.') }}</td>
                  </tr>
                  @empty
                  <tr><td colspan="4" style="padding:20px; text-align:center; color:#9ca3af;">Belum ada riwayat pembayaran.</td></tr>
                  @endforelse
              </tbody>
          </table>
      </div>
  </div>

  <p class="section-title"><i class="ti ti-notes"></i> Notulensi Rapat yang Diikuti</p>
  <div class="master-card" style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; margin-bottom:24px;">
      <div class="table-responsive">
          <table style="width:100%; border-collapse:collapse; font-size:13px; text-align:left;">
              <thead style="background:#f8fafc; font-size:11px; color:#475569; text-transform:uppercase;">
                  <tr>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Tanggal</th>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Judul Rapat</th>
                      <th style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($notulensiList as $notul)
                  <tr>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">{{ $notul->tanggal->format('d/m/Y') }}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; font-weight:500;">{{ $notul->judul }}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0;">
                          @if($notul->nomor_bap)
                              <a href="{{ route('notulensi.exportBap', $notul->id) }}" target="_blank" style="background:#f1f5f9; color:#0f766e; border:none; padding:6px 12px; border-radius:6px; font-size:12px; text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-weight:500;"><i class="ti ti-download"></i> BAP</a>
                          @else
                              <span style="color:#9ca3af; font-size:11px;">Belum ada BAP</span>
                          @endif
                      </td>
                  </tr>
                  @empty
                  <tr><td colspan="3" style="padding:20px; text-align:center; color:#9ca3af;">Belum ada notulensi rapat.</td></tr>
                  @endforelse
              </tbody>
          </table>
      </div>
  </div>

  {{-- Modal Bayar Tagihan (Dosen) --}}
  <div class="custom-modal" id="bayarModal">
      <div class="custom-modal-content" style="width:420px;">
          <div class="custom-modal-header">
              <span>Pembayaran Tagihan</span>
              <button class="icon-btn" onclick="closeBayarModal()"><i class="ti ti-x"></i></button>
          </div>
          <form id="bayarForm" onsubmit="submitBayar(event)" x-data="bayarForm()">
              <div class="custom-modal-body">
                  <input type="hidden" id="bayar_tagihan_id">

                  <div id="bayarInfo" style="background:#f8fafc; border-radius:8px; padding:14px; margin-bottom:16px; font-size:13px;">
                      {{-- Filled by JS --}}
                  </div>

                  <div class="form-group" style="margin-bottom:16px;">
                      <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:6px">Jumlah Bayar (Rp)</label>
                      <input type="number" id="bayar_jumlah" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;" min="1" step="1" required placeholder="cth: 50000" x-model="jumlah" @input="hitung()">
                      
                      {{-- Preview pembagian --}}
                      <div x-show="jumlah > 0" class="mt-3 p-3 rounded-lg text-sm" style="background:#eff6ff; margin-top:10px; border-radius:8px; padding:12px; display:none;" :style="jumlah > 0 ? 'display:block' : 'display:none'">
                          <p style="font-weight:600; color:#1d4ed8; margin-bottom:6px;">Pembagian Otomatis:</p>
                          <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                              <span style="color:#4b5563;">Tabungan (33,33%)</span>
                              <span style="font-family:monospace; font-weight:600; color:#059669;">Rp <span x-text="formatRp(tabungan)"></span></span>
                          </div>
                          <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                              <span style="color:#4b5563;">Uang Sosial (66,67%)</span>
                              <span style="font-family:monospace; font-weight:600; color:#2563eb;">Rp <span x-text="formatRp(sosial)"></span></span>
                          </div>
                      </div>
                  </div>
                  
                  <div class="form-group" style="margin-bottom:16px;">
                      <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:6px">Tanggal Bayar</label>
                      <input type="date" id="bayar_tanggal" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;" required value="{{ now()->format('Y-m-d') }}">
                  </div>
                  <div class="form-group" style="margin-bottom:16px;">
                      <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:6px">Keterangan <span style="color:#94a3b8;">(opsional)</span></label>
                      <input type="text" id="bayar_keterangan" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;" placeholder="cth: Pembayaran via transfer">
                  </div>
                  
                  <div class="form-group" style="margin-top:16px;">
                      <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:6px">
                          Bukti Pembayaran <span style="color:#6b7280; font-weight:400">(opsional)</span>
                      </label>
                      <div id="dropzone" style="border:2px dashed #d1d5db; border-radius:8px; padding:20px; text-align:center; cursor:pointer; background:#f9fafb" onclick="document.getElementById('bayar_bukti_foto').click()">
                          <i class="ti ti-photo-up" style="font-size:28px;color:#9ca3af"></i>
                          <p style="font-size:12px; color:#6b7280; margin-top:6px">
                              Klik atau drag foto ke sini<br>
                              <span style="font-size:11px">JPG, PNG, WEBP — Maks 2MB</span>
                          </p>
                      </div>
                      <input type="file" id="bayar_bukti_foto" accept="image/png, image/jpeg, image/jpg, image/webp" style="display:none" onchange="previewFoto(this)">
                      <div id="previewContainer" style="display:flex; flex-wrap:wrap; gap:8px; margin-top:10px"></div>
                  </div>
              </div>
              <div class="custom-modal-footer">
                  <button type="button" class="btn-outline" onclick="closeBayarModal()" style="padding:8px 16px; border:1px solid #d1d5db; background:#fff; border-radius:6px; cursor:pointer;">Batal</button>
                  <button type="submit" style="background:#059669; color:#fff; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;"><i class="ti ti-cash"></i> Bayar</button>
              </div>
          </form>
      </div>
  </div>

@endif

@endsection

@push('scripts')
<script>
  const csrfToken = '{{ csrf_token() }}';

  @if($user->role === 'dosen')
  function openBayarModal(id, jumlah, dibayar, bulanStr, tahun) {
      const sisa = jumlah - dibayar;
      document.getElementById('bayar_tagihan_id').value = id;
      document.getElementById('bayarInfo').innerHTML = `
          Tagihan Bulan: <strong>${bulanStr} ${tahun}</strong><br>
          Sisa Tagihan: <strong style="color:#dc2626;">Rp ${parseInt(sisa).toLocaleString('id-ID')}</strong>
      `;
      document.getElementById('bayarForm').reset();
      document.getElementById('bayar_jumlah').value = Math.ceil(sisa);
      document.getElementById('bayar_jumlah').max = Math.ceil(sisa);
      document.getElementById('bayar_tanggal').value = new Date().toISOString().split('T')[0];
      
      hapusFoto();
      
      document.getElementById('bayarModal').classList.add('active');

      // Trigger Alpine x-model
      setTimeout(() => {
          document.getElementById('bayar_jumlah').dispatchEvent(new Event('input'));
      }, 100);
  }

  function closeBayarModal() {
      document.getElementById('bayarModal').classList.remove('active');
  }

  async function submitBayar(e) {
      e.preventDefault();
      const id = document.getElementById('bayar_tagihan_id').value;
      const formData = new FormData();
      formData.append('jumlah_bayar', document.getElementById('bayar_jumlah').value);
      formData.append('tanggal_bayar', document.getElementById('bayar_tanggal').value);
      formData.append('keterangan', document.getElementById('bayar_keterangan').value);

      const fotoInput = document.getElementById('bayar_bukti_foto');
      if (fotoInput && fotoInput.files[0]) {
          formData.append('bukti_foto', fotoInput.files[0]);
      }

      try {
          const res = await fetch(`/kas/tagihan/${id}/bayar`, {
              method: 'POST',
              headers: { 
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': csrfToken 
              },
              body: formData
          });
          
          if (!res.ok) {
              const errorData = await res.json();
              alert(errorData.message || 'Gagal menyimpan data.');
              return;
          }

          const data = await res.json();
          if (data.success) {
              closeBayarModal();
              window.location.reload();
          } else {
              alert(data.message || 'Gagal menyimpan.');
          }
      } catch (err) {
          console.error(err);
          alert('Terjadi kesalahan jaringan atau server. Coba lagi.');
      }
  }

  function previewFoto(input) {
      const container = document.getElementById('previewContainer');
      container.innerHTML = '';
      if (input.files && input.files[0]) {
          const file = input.files[0];
          if (file.size > 2 * 1024 * 1024) {
              alert(file.name + ' melebihi 2MB!');
              input.value = '';
              return;
          }
          const reader = new FileReader();
          reader.onload = (e) => {
              const div = document.createElement('div');
              div.style.cssText = 'position:relative;width:80px;height:80px';
              div.innerHTML = `
                  <img src="${e.target.result}" style="width:80px;height:80px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0"/>
                  <span onclick="hapusFoto()" style="position:absolute; top:-6px;right:-6px; background:#ef4444; color:#fff; border-radius:50%; width:18px;height:18px; font-size:11px; display:flex; align-items:center; justify-content:center; cursor:pointer">×</span>
                  <p style="font-size:9px; color:#6b7280; text-align:center; margin-top:2px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis">${file.name}</p>`;
              container.appendChild(div);
          };
          reader.readAsDataURL(file);
      }
  }

  function hapusFoto() {
      const input = document.getElementById('bayar_bukti_foto');
      if (input) input.value = '';
      const container = document.getElementById('previewContainer');
      if (container) container.innerHTML = '';
  }

  function bayarForm() {
      return {
          jumlah: 0,
          tabungan: 0,
          sosial: 0,
          hitung() {
              this.tabungan = Math.round(this.jumlah * 0.3333);
              this.sosial   = this.jumlah - this.tabungan;
          },
          formatRp(val) {
              return Number(val).toLocaleString('id-ID');
          }
      }
  }
  @endif
</script>
@endpush
