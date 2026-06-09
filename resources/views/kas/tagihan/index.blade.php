@extends('layouts.dashboard')
@section('title', 'Tagihan Dosen')
@section('title_addon', 'Total: ' . $tagihanList->total())
@section('subtitle', 'Kelola tagihan iuran kas dosen')

@section('topbar_actions')
    @if(in_array(auth()->user()->role, ['super_admin', 'admin_fst', 'admin_fis']))
    <button class="btn-primary" onclick="generateOtomatis()" style="background:#0f766e;">
        <i class="ti ti-loader"></i> Generate Tagihan Otomatis
    </button>
    <button class="btn-primary" onclick="openModal()">
        <i class="ti ti-plus"></i> Tambah Tagihan
    </button>
    @endif
    <a href="{{ route('kas.tagihan.exportPdf', request()->query()) }}" class="btn-outline" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
        <i class="ti ti-file-export"></i> Export PDF
    </a>
@endsection

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

    .status-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .status-lunas { background: #d1fae5; color: #065f46; }
    .status-belum { background: #fee2e2; color: #991b1b; }

    .fak-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .fak-fst  { background: #dbeafe; color: #1d4ed8; }
    .fak-fis  { background: #fef3c7; color: #92400e; }
    .fak-other{ background: #e2e8f0; color: #475569; }

    .btn-primary { background: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-outline { background: #fff; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; }
    .btn-outline:hover { background: #f1f5f9; }
    .btn-success { background: #059669; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
    .btn-success:hover { background: #047857; }
    .icon-btn { background: none; border: none; cursor: pointer; color: #64748b; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; }
    .icon-btn:hover { background: #f1f5f9; color: #2563eb; }
    .icon-btn.delete:hover { color: #ef4444; }

    .filter-control { border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; font-size: 13px; outline: none; width: 100%; }
    .filter-control:focus { border-color: #2563eb; }

    .custom-modal { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.5); align-items: center; justify-content: center; z-index: 50; }
    .custom-modal.active { display: flex; }
    .custom-modal-content { background: #fff; width: 520px; max-height: 90vh; overflow-y: auto; border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,.15); font-family: 'Plus Jakarta Sans', sans-serif; }
    .custom-modal-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; font-weight: 600; font-size: 16px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: #fff; z-index: 1; }
    .custom-modal-body { padding: 22px; font-size: 13px; }
    .custom-modal-footer { padding: 16px 22px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; border-radius: 0 0 14px 14px; position: sticky; bottom: 0; }
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 6px; color: #475569; }

    .detail-modal-content { background: #fff; width: 540px; max-height: 90vh; overflow-y: auto; border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,.15); }
    .detail-section { padding: 16px 22px; border-bottom: 1px solid #f1f5f9; }
    .detail-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .detail-value { font-size: 13px; color: #1e293b; }

    .dosen-label:hover { background: #f8fafc; }
    .dosen-label input[type=radio] { width: 15px; height: 15px; accent-color: #2563eb; cursor: pointer; margin-top:2px; }
    .dosen-label input[type=radio]:checked + span { color: #2563eb; font-weight: 600; }

    .pag-wrap { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b; }
    .pag-btn { padding: 6px 14px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; color: #475569; background: #fff; text-decoration: none; }
    .pag-btn:hover { background: #f1f5f9; }
    .pag-btn.disabled { opacity: .45; pointer-events: none; }

    #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
    .custom-toast { min-width: 260px; background: #fff; border-left: 4px solid #10b981; box-shadow: 0 4px 12px rgba(0,0,0,.1); padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideIn .3s ease forwards; }
    .custom-toast.error { border-left-color: #ef4444; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
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
</style>
@endpush

@section('content')

@php $authUser = auth()->user(); @endphp

<div id="toast-container"></div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('kas.tagihan') }}" class="master-card p-4 mb-4" id="filterForm" x-data="{
    submitSPA() {
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams(formData);
        Livewire.navigate(document.getElementById('filterForm').action + '?' + params.toString());
    }
}" @submit.prevent="submitSPA">
    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <input type="text" name="search" class="filter-control" placeholder="Cari nama dosen..." value="{{ request('search') }}" style="flex:1; min-width:200px;" @input.debounce.500ms="submitSPA">
        @if(in_array($authUser->role, ['super_admin', 'kepala_unit']))
        <select name="fakultas_id" class="filter-control" style="width:180px;" @change="submitSPA">
            <option value="">Semua Fakultas</option>
            @foreach($fakultasList as $fak)
                <option value="{{ $fak->id }}" {{ request('fakultas_id') == $fak->id ? 'selected' : '' }}>
                    {{ $fak->nama_fakultas }}
                </option>
            @endforeach
        </select>
        @endif
        <select name="status" class="filter-control" style="width:140px;" @change="submitSPA">
            <option value="">Semua Status</option>
            <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
        </select>
        <select name="bulan" class="filter-control" style="width:120px;" @change="submitSPA">
            <option value="">Bulan</option>
            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $nama)
                <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $nama }}</option>
            @endforeach
        </select>
        <select name="tahun" class="filter-control" style="width:110px;" @change="submitSPA">
            <option value="">Tahun</option>
            @foreach(range(now()->year, now()->year - 5) as $t)
                <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <a wire:navigate href="{{ route('kas.tagihan') }}" class="btn-outline">Reset</a>
    </div>
</form>

{{-- Summary Cards --}}
<div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:24px;">
    <div class="stat-card stat-saldo" style="flex:1; min-width:200px;">
        <span class="stat-label">Total Tagihan</span>
        <span class="stat-number" style="color:#475569;">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
    </div>
    <div class="stat-card stat-masuk" style="flex:1; min-width:200px;">
        <span class="stat-label">Total Terbayar</span>
        <span class="stat-number" style="color:#059669;">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
    </div>
    <div class="stat-card stat-keluar" style="flex:1; min-width:200px;">
        <span class="stat-label">Sisa Tagihan</span>
        <span class="stat-number" style="color:#dc2626;">Rp {{ number_format($totalSisa, 0, ',', '.') }}</span>
    </div>
</div>

{{-- Table --}}
<div class="master-card">
    <div class="table-responsive">
        <table class="master-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Dosen</th>
                    <th>Fakultas</th>
                    <th>Periode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihanList as $index => $tagihan)
                <tr>
                    <td>{{ $tagihanList->firstItem() + $index }}</td>
                    <td style="font-weight:500;">{{ $tagihan->dosen->nama_lengkap ?? '-' }}</td>
                    <td>
                        @if($tagihan->fakultas)
                            @php
                                $fakNama = $tagihan->fakultas->nama_fakultas ?? '-';
                                $fakClass = str_contains(strtolower($fakNama), 'sains') ? 'fak-fst'
                                          : (str_contains(strtolower($fakNama), 'sosial') ? 'fak-fis' : 'fak-other');
                            @endphp
                            <span class="fak-badge {{ $fakClass }}">{{ $fakNama }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][($tagihan->bulan ?? 1) - 1] }} {{ $tagihan->tahun }}</td>
                    <td style="font-weight:600;">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge {{ $tagihan->status === 'lunas' ? 'status-lunas' : 'status-belum' }}">
                            {{ $tagihan->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </td>
                    <td>
                        <button class="icon-btn" onclick="viewDetail({{ $tagihan->id }})" title="Lihat Detail">
                            <i class="ti ti-eye"></i>
                        </button>
                        @if($authUser->role !== 'kepala_unit')
                            @if($tagihan->status !== 'lunas')
                            @php
                                $canPayTag = $authUser->role === 'super_admin' || $authUser->role === 'dosen' || (int) $tagihan->fakultas_id === (int) $authUser->fakultas_id;
                            @endphp
                            @if($canPayTag)
                            <button class="icon-btn" onclick="openBayarModal({{ $tagihan->id }})" title="Bayar" style="color:#059669;">
                                <i class="ti ti-cash"></i>
                            </button>
                            @endif
                            @endif
                            
                            @if($authUser->role !== 'dosen')
                            @php
                                $canDeleteTag = $authUser->role === 'super_admin' || (int) $tagihan->fakultas_id === (int) $authUser->fakultas_id;
                            @endphp
                            @if($canDeleteTag)
                            <button class="icon-btn delete" onclick="deleteTagihan({{ $tagihan->id }})" title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                            @endif
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                        <i class="ti ti-receipt" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                        Belum ada data tagihan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pag-wrap">
        <div>
            Menampilkan <b>{{ $tagihanList->firstItem() ?? 0 }}</b>–<b>{{ $tagihanList->lastItem() ?? 0 }}</b>
            dari <b>{{ $tagihanList->total() }}</b> data
        </div>
        <div style="display:flex; gap:6px;">
            @if($tagihanList->onFirstPage())
                <span class="pag-btn disabled">← Previous</span>
            @else
                <a wire:navigate href="{{ $tagihanList->previousPageUrl() }}" class="pag-btn">← Previous</a>
            @endif
            @if($tagihanList->hasMorePages())
                <a wire:navigate href="{{ $tagihanList->nextPageUrl() }}" class="pag-btn">Next →</a>
            @else
                <span class="pag-btn disabled">Next →</span>
            @endif
        </div>
    </div>
</div>

{{-- Form Modal (Tambah Tagihan) --}}
<div class="custom-modal" id="tagihanModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <span id="modalTitle">Tambah Tagihan</span>
            <button class="icon-btn" onclick="closeModal()"><i class="ti ti-x"></i></button>
        </div>
        <form id="tagihanForm" onsubmit="saveTagihan(event)">
            <div class="custom-modal-body">
                <input type="hidden" id="tagihan_id">

                <div class="form-group">
                    <label>Pilih Dosen</label>
                    <input type="text" id="searchDosen" class="filter-control" placeholder="Ketik nama dosen..." onkeyup="filterDosen()" style="margin-bottom:8px;">
                    <div id="dosenList" style="display:flex; flex-direction:column; gap:6px; max-height:220px; overflow-y:auto; padding-right:4px;">
                        @foreach($dosenList as $d)
                        <label class="dosen-label" data-nama="{{ strtolower($d->nama_lengkap) }}" style="display:flex; align-items:center; gap:8px; padding:8px; border:1px solid #e2e8f0; border-radius:6px; cursor:pointer; margin:0;">
                            <input type="radio" name="dosen_id" value="{{ $d->id }}" required>
                            <span>
                                <b>{{ $d->nama_lengkap }}</b>
                                <span style="color:#94a3b8; font-size:11px;"> — {{ $d->prodi->nama_prodi ?? '-' }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label>Bulan</label>
                        <select id="tagihan_bulan" class="filter-control" required>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $nama)
                                <option value="{{ $i+1 }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tahun</label>
                        <select id="tagihan_tahun" class="filter-control" required>
                            @foreach(range(now()->year, now()->year - 5) as $t)
                                <option value="{{ $t }}" {{ $t == now()->year ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Jumlah Tagihan (Rp)</label>
                    <input type="number" id="tagihan_jumlah" class="filter-control" min="1" step="1" required placeholder="cth: 100000">
                </div>

                <div class="form-group">
                    <label>Tanggal Jatuh Tempo <span style="color:#94a3b8;">(opsional)</span></label>
                    <input type="date" id="tagihan_jatuh_tempo" class="filter-control">
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Bayar --}}
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

                <div class="form-group">
                    <label>Jumlah Bayar (Rp)</label>
                    <input type="number" id="bayar_jumlah" class="filter-control" min="1" step="1" required placeholder="cth: 50000" x-model="jumlah" @input="hitung()">
                    
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
                <div class="form-group">
                    <label>Tanggal Bayar</label>
                    <input type="date" id="bayar_tanggal" class="filter-control" required value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label>Keterangan <span style="color:#94a3b8;">(opsional)</span></label>
                    <input type="text" id="bayar_keterangan" class="filter-control" placeholder="cth: Pembayaran partial">
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
                <button type="button" class="btn-outline" onclick="closeBayarModal()">Batal</button>
                <button type="submit" class="btn-success"><i class="ti ti-cash"></i> Bayar</button>
            </div>
        </form>
    </div>
</div>

{{-- Detail Modal --}}
<div class="custom-modal" id="detailModal">
    <div class="detail-modal-content">
        <div class="custom-modal-header">
            <span id="detailTitle">Detail Tagihan</span>
            <button class="icon-btn" onclick="closeDetail()"><i class="ti ti-x"></i></button>
        </div>
        <div id="detailBody" style="padding:0;">
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn-outline" onclick="closeDetail()">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    window.showToast = function(msg, type = 'success') {
        const t = document.createElement('div');
        t.className = `custom-toast ${type === 'error' ? 'error' : ''}`;
        t.innerHTML = `<i class="ti ti-${type === 'success' ? 'check' : 'alert-circle'}"></i> ${msg}`;
        document.getElementById('toast-container').appendChild(t);
        setTimeout(() => t.remove(), 3500);
    }

    window.openModal = function() {
        document.getElementById('tagihanForm').reset();
        document.getElementById('tagihan_id').value = '';
        document.getElementById('modalTitle').textContent = 'Tambah Tagihan';
        document.getElementById('tagihan_tahun').value = new Date().getFullYear();
        document.getElementById('tagihan_bulan').value = new Date().getMonth() + 1;
        document.getElementById('searchDosen').value = '';
        document.querySelectorAll('input[name="dosen_id"]').forEach(r => r.checked = false);
        document.querySelectorAll('#dosenList label').forEach(l => l.style.display = 'flex');
        document.getElementById('tagihanModal').classList.add('active');
    }

    window.closeModal = function() {
        document.getElementById('tagihanModal').classList.remove('active');
    }

    window.closeBayarModal = function() {
        document.getElementById('bayarModal').classList.remove('active');
    }

    window.closeDetail = function() {
        document.getElementById('detailModal').classList.remove('active');
    }

    /* ── Foto Preview ──────────────────────────────── */
    window.previewFoto = function(input) {
        const container = document.getElementById('previewContainer');
        container.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 2 * 1024 * 1024) {
                window.showToast(file.name + ' melebihi 2MB!', 'error');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.style.cssText = 'position:relative;width:80px;height:80px';
                div.innerHTML = `
                    <img src="${e.target.result}" style="width:80px;height:80px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0"/>
                    <span onclick="window.hapusFoto()" style="position:absolute; top:-6px;right:-6px; background:#ef4444; color:#fff; border-radius:50%; width:18px;height:18px; font-size:11px; display:flex; align-items:center; justify-content:center; cursor:pointer">×</span>
                    <p style="font-size:9px; color:#6b7280; text-align:center; margin-top:2px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis">${file.name}</p>`;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    }

    window.hapusFoto = function() {
        const input = document.getElementById('bayar_bukti_foto');
        if (input) input.value = '';
        const container = document.getElementById('previewContainer');
        if (container) container.innerHTML = '';
    }

    window.filterDosen = function() {
        const q = document.getElementById('searchDosen').value.toLowerCase();
        document.querySelectorAll('#dosenList label').forEach(label => {
            label.style.display = label.dataset.nama.includes(q) ? 'flex' : 'none';
        });
    }

    window.viewDetail = async function(id) {
        try {
            const res = await fetch(`/kas/tagihan/${id}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() }
            });
            const data = await res.json();

            const namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            const fakNama = data.fakultas ? data.fakultas.nama_fakultas : '-';
            const isLunas = data.status === 'lunas';

            document.getElementById('detailTitle').textContent = 'Detail Tagihan';
            document.getElementById('detailBody').innerHTML = `
                <div class="detail-section">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <div class="detail-label">Dosen</div>
                            <div class="detail-value">${data.dosen ? data.dosen.nama_lengkap : '-'}</div>
                        </div>
                        <div>
                            <div class="detail-label">Fakultas</div>
                            <div class="detail-value">${fakNama}</div>
                        </div>
                    </div>
                </div>
                <div class="detail-section">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <div class="detail-label">Periode</div>
                            <div class="detail-value">${namaBulan[(data.bulan || 1) - 1]} ${data.tahun}</div>
                        </div>
                        <div>
                            <div class="detail-label">Jumlah Tagihan</div>
                            <div class="detail-value" style="font-weight:600;">Rp ${parseInt(data.jumlah).toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                </div>
                ${!isLunas ? `
                <div class="detail-section">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <div class="detail-label">Sisa Tagihan</div>
                            <div class="detail-value" style="font-weight:600; color:#dc2626;">Rp ${parseInt(data.jumlah - data.dibayar_amount).toLocaleString('id-ID')}</div>
                        </div>
                        <div>
                            <div class="detail-label">Jatuh Tempo</div>
                            <div class="detail-value">${data.tanggal_jatuh_tempo ? new Date(data.tanggal_jatuh_tempo).toLocaleDateString('id-ID') : '-'}</div>
                        </div>
                    </div>
                </div>` : ''}
                <div class="detail-section">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="status-badge ${isLunas ? 'status-lunas' : 'status-belum'}">
                                    ${isLunas ? 'Lunas' : 'Belum Lunas'}
                                </span>
                            </div>
                        </div>
                        ${isLunas ? `
                        <div>
                            <div class="detail-label">Tanggal Bayar</div>
                            <div class="detail-value">${data.dibayar_tanggal ? new Date(data.dibayar_tanggal).toLocaleDateString('id-ID') : '-'}</div>
                        </div>` : ''}
                    </div>
                </div>
                <div class="detail-section" style="border-bottom:none;">
                    <div class="detail-label">Dibuat Oleh</div>
                    <div class="detail-value">${data.user ? data.user.name : '-'}</div>
                </div>
            `;
            document.getElementById('detailModal').classList.add('active');
        } catch {
            showToast('Gagal memuat detail.', 'error');
        }
    }

    window.openBayarModal = async function(id) {
        try {
            const res = await fetch(`/kas/tagihan/${id}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() }
            });
            const data = await res.json();

            const namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            const sisa = data.jumlah - data.dibayar_amount;

            document.getElementById('bayar_tagihan_id').value = id;
            document.getElementById('bayarInfo').innerHTML = `
                <strong>${data.dosen ? data.dosen.nama_lengkap : '-'}</strong><br>
                ${namaBulan[(data.bulan || 1) - 1]} ${data.tahun}<br>
                Tagihan: <strong>Rp ${parseInt(data.jumlah).toLocaleString('id-ID')}</strong><br>
                Sudah Dibayar: <strong>Rp ${parseInt(data.dibayar_amount).toLocaleString('id-ID')}</strong><br>
                Sisa: <strong style="color:#dc2626;">Rp ${parseInt(sisa).toLocaleString('id-ID')}</strong>
            `;
            document.getElementById('bayarForm').reset();
            document.getElementById('bayar_jumlah').value = Math.ceil(sisa);
            document.getElementById('bayar_jumlah').max = Math.ceil(sisa);
            document.getElementById('bayar_tanggal').value = new Date().toISOString().split('T')[0];
            document.getElementById('bayarModal').classList.add('active');

            // Trigger Alpine x-model
            setTimeout(() => {
                document.getElementById('bayar_jumlah').dispatchEvent(new Event('input'));
            }, 100);
        } catch {
            showToast('Gagal memuat data.', 'error');
        }
    }

    window.submitBayar = async function(e) {
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
                headers: { 'X-CSRF-TOKEN': getCsrfToken(), 'Accept': 'application/json' },
                body: formData,
            });
            const data = await res.json();

            if (res.ok && data.success) {
                window.showToast(data.message, 'success');
                window.closeBayarModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                window.showToast(data.message || 'Terjadi kesalahan.', 'error');
            }
        } catch {
            window.showToast('Koneksi gagal.', 'error');
        }
    }

    window.saveTagihan = async function(e) {
        e.preventDefault();

        const checked = document.querySelector('input[name="dosen_id"]:checked');
        if (!checked) {
            window.showToast('Pilih dosen terlebih dahulu.', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('dosen_id', checked.value);
        formData.append('bulan', document.getElementById('tagihan_bulan').value);
        formData.append('tahun', document.getElementById('tagihan_tahun').value);
        formData.append('jumlah', document.getElementById('tagihan_jumlah').value);
        const jatuhTempo = document.getElementById('tagihan_jatuh_tempo').value;
        if (jatuhTempo) formData.append('tanggal_jatuh_tempo', jatuhTempo);

        try {
            const res = await fetch('{{ route("kas.tagihan.store") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': getCsrfToken(), 'Accept': 'application/json' },
                body: formData,
            });
            const data = await res.json();

            if (res.ok && data.success) {
                window.showToast(data.message, 'success');
                window.closeModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                window.showToast(data.message || 'Terjadi kesalahan.', 'error');
            }
        } catch {
            window.showToast('Koneksi gagal.', 'error');
        }
    }

    window.deleteTagihan = async function(id) {
        if (!confirm('Yakin ingin menghapus tagihan ini?')) return;
        try {
            const res = await fetch(`/kas/tagihan/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken(), 'Accept': 'application/json' },
                body: JSON.stringify({ _method: 'DELETE' }),
            });
            const data = await res.json();
            if (res.ok && data.success) {
                window.showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                window.showToast(data.message || 'Gagal menghapus.', 'error');
            }
        } catch {
            window.showToast('Koneksi gagal.', 'error');
        }
    }

    window.generateOtomatis = async function() {
        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const currentMonth = new Date().getMonth();
        const currentYear = new Date().getFullYear();
        if (!confirm(`Yakin ingin generate otomatis tagihan sesuai nominal masing-masing dosen untuk bulan ${monthNames[currentMonth]} ${currentYear}?`)) {
            return;
        }

        try {
            const res = await fetch('{{ route("kas.tagihan.generateOtomatis") }}', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': getCsrfToken(), 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json' 
                },
                body: JSON.stringify({
                    bulan: currentMonth + 1,
                    tahun: currentYear
                })
            });
            const data = await res.json();
            if (res.ok && data.success) {
                window.showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                window.showToast(data.message || 'Terjadi kesalahan.', 'error');
            }
        } catch {
            window.showToast('Koneksi gagal.', 'error');
        }
    }

    window.bayarForm = function() {
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
</script>
@endpush