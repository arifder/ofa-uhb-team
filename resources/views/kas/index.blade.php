@extends('layouts.dashboard')
@section('title', 'Kas ' . $title)

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

    .fak-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .fak-fst  { background: #dbeafe; color: #1d4ed8; }
    .fak-fis  { background: #fef3c7; color: #92400e; }
    .fak-other{ background: #e2e8f0; color: #475569; }

    .jumlah-masuk { color: #059669; font-weight: 600; }
    .jumlah-keluar { color: #dc2626; font-weight: 600; }

    .btn-primary { background: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-outline { background: #fff; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; }
    .btn-outline:hover { background: #f1f5f9; }
    .icon-btn { background: none; border: none; cursor: pointer; color: #64748b; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; }
    .icon-btn:hover { background: #f1f5f9; color: #2563eb; }
    .icon-btn.delete:hover { color: #ef4444; }

    .filter-control { border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; font-size: 13px; outline: none; width: 100%; }
    .filter-control:focus { border-color: #2563eb; }

    .custom-modal { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.5); align-items: center; justify-content: center; z-index: 50; }
    .custom-modal.active { display: flex; }
    .custom-modal-content { background: #fff; width: 640px; max-height: 90vh; overflow-y: auto; border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,.15); font-family: 'Plus Jakarta Sans', sans-serif; }
    .custom-modal-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; font-weight: 600; font-size: 16px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: #fff; z-index: 1; }
    .custom-modal-body { padding: 22px; font-size: 13px; }
    .custom-modal-footer { padding: 16px 22px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; border-radius: 0 0 14px 14px; position: sticky; bottom: 0; }
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 6px; color: #475569; }

    .detail-modal-content { background: #fff; width: 500px; max-height: 90vh; overflow-y: auto; border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,.15); }
    .detail-section { padding: 16px 22px; border-bottom: 1px solid #f1f5f9; }
    .detail-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .detail-value { font-size: 13px; color: #1e293b; }

    .pag-wrap { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b; }
    .pag-btn { padding: 6px 14px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; color: #475569; background: #fff; text-decoration: none; }
    .pag-btn:hover { background: #f1f5f9; }
    .pag-btn.disabled { opacity: .45; pointer-events: none; }

    #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
    .custom-toast { min-width: 260px; background: #fff; border-left: 4px solid #10b981; box-shadow: 0 4px 12px rgba(0,0,0,.1); padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideIn .3s ease forwards; }
    .custom-toast.error { border-left-color: #ef4444; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

    /* ── Searchable Select ──────────────────────── */
    .search-select { position: relative; }
    .search-select-input {
        width: 100%; border: 1px solid #e2e8f0; padding: 8px 32px 8px 12px; border-radius: 8px;
        font-size: 13px; outline: none; background: #fff; cursor: pointer;
        text-overflow: ellipsis; white-space: nowrap; overflow: hidden;
    }
    .search-select-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.08); }
    .search-select-input::placeholder { color: #94a3b8; }
    .search-select-chevron {
        position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; pointer-events: auto;
        color: #94a3b8; font-size: 14px;
    }
    .search-select-dropdown {
        position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 60;
        background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,.12); max-height: 220px; overflow-y: auto;
    }
    .search-select-option {
        padding: 9px 14px; cursor: pointer; font-size: 13px; color: #1e293b;
        display: flex; align-items: center; gap: 8px; transition: background .1s;
    }
    .search-select-option:hover, .search-select-option.highlighted { background: #f1f5f9; }
    .search-select-option.selected { background: #EFF6FF; color: #1D4ED8; font-weight: 500; }
    .search-select-option small { color: #94a3b8; font-weight: 400; }
    .search-select-empty { padding: 16px; text-align: center; color: #94a3b8; font-size: 12px; }
</style>
@endpush

@section('content')

@php $authUser = auth()->user(); @endphp

{{-- Page Header --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:18px; font-weight:600; color:#1e293b;">
        Kas {{ $title }}
        <span style="font-size:13px; font-weight:400; color:#64748b; margin-left:8px;">Total: {{ $kasList->total() }}</span>
    </h2>
    @if(in_array($authUser->role, ['super_admin', 'admin_fst', 'admin_fis']))
    <button class="btn-primary" onclick="openModal()">
        <i class="ti ti-plus"></i> Tambah Kas {{ $title }}
    </button>
    @endif
</div>

<div id="toast-container"></div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('kas.' . $jenis) }}" class="master-card p-4 mb-4">
    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <input type="text" name="search" class="filter-control" placeholder="Cari keterangan..." value="{{ request('search') }}" style="flex:1; min-width:200px;">
        @if(in_array($authUser->role, ['super_admin', 'kepala_unit']))
        <select name="fakultas_id" class="filter-control" style="width:180px;" onchange="this.form.submit()">
            <option value="">Semua Fakultas</option>
            @foreach($fakultasList as $fak)
                <option value="{{ $fak->id }}" {{ request('fakultas_id') == $fak->id ? 'selected' : '' }}>
                    {{ $fak->nama_fakultas }}
                </option>
            @endforeach
        </select>
        @endif
        <input type="date" name="tanggal_awal" class="filter-control" style="width:150px;" value="{{ request('tanggal_awal') }}" placeholder="Tgl Awal">
        <input type="date" name="tanggal_akhir" class="filter-control" style="width:150px;" value="{{ request('tanggal_akhir') }}" placeholder="Tgl Akhir">
        <button type="submit" class="btn-outline">Filter</button>
        <a href="{{ route('kas.' . $jenis) }}" class="btn-outline">Reset</a>
    </div>
</form>

{{-- Table --}}
<div class="master-card">
    <table class="master-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Fakultas</th>
                @if($jenis === 'masuk')
                <th>Dosen</th>
                <th>Pembagian (Tabungan / Sosial)</th>
                @elseif($jenis === 'keluar')
                <th>Kategori</th>
                @endif
                <th>Jumlah</th>
                <th>Dibuat Oleh</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kasList as $index => $kas)
            <tr>
                <td>{{ $kasList->firstItem() + $index }}</td>
                <td>{{ \Carbon\Carbon::parse($kas->tanggal)->translatedFormat('d M Y') }}</td>
                <td>
                    <a href="#" onclick="viewDetail({{ $kas->id }})" style="color:#2563eb; font-weight:500; text-decoration:none;">
                        {{ $kas->keterangan }}
                    </a>
                </td>
                <td>
                    @if($kas->fakultas)
                        @php
                            $fakNama = $kas->fakultas->nama_fakultas ?? '-';
                            $fakClass = str_contains(strtolower($fakNama), 'sains') ? 'fak-fst'
                                      : (str_contains(strtolower($fakNama), 'sosial') ? 'fak-fis' : 'fak-other');
                        @endphp
                        <span class="fak-badge {{ $fakClass }}">{{ $fakNama }}</span>
                    @else
                        -
                    @endif
                </td>
                @if($jenis === 'masuk')
                <td>{{ $kas->dosen->nama_lengkap ?? '-' }}</td>
                <td>
                    @if($kas->dosen_id && $kas->jumlah > 0)
                    <div style="font-size:13px;">
                        <span style="color:#059669; font-family:monospace;">Rp {{ number_format($kas->tabungan, 0, ',', '.') }}</span>
                        <span style="color:#9ca3af; margin:0 4px;">/</span>
                        <span style="color:#2563eb; font-family:monospace;">Rp {{ number_format($kas->uang_sosial, 0, ',', '.') }}</span>
                    </div>
                    <div style="font-size:11px; color:#9ca3af;">Tabungan / Sosial</div>
                    @else
                    -
                    @endif
                </td>
                @elseif($jenis === 'keluar')
                <td>
                    @if($kas->kategori)
                        <span style="background:#f1f5f9; padding:4px 8px; border-radius:6px; font-size:12px; color:#475569; text-transform:capitalize;">
                            {{ str_replace('_', ' ', $kas->kategori) }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                @endif
                <td class="{{ $jenis === 'masuk' ? 'jumlah-masuk' : 'jumlah-keluar' }}">
                    {{ $jenis === 'masuk' ? '+' : '-' }}{{ number_format($kas->jumlah, 0, ',', '.') }}
                </td>
                <td style="color:#64748b; font-size:12px;">{{ $kas->user->name ?? '-' }}</td>
                <td>
                    <button class="icon-btn" onclick="viewDetail({{ $kas->id }})" title="Lihat Detail">
                        <i class="ti ti-eye"></i>
                    </button>
                    @if(in_array($authUser->role, ['super_admin', 'admin_fst', 'admin_fis']))
                    <button class="icon-btn" onclick="editKas({{ $kas->id }})" title="Edit">
                        <i class="ti ti-pencil"></i>
                    </button>
                    <button class="icon-btn delete" onclick="deleteKas({{ $kas->id }})" title="Hapus">
                        <i class="ti ti-trash"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $jenis === 'masuk' ? 9 : 7 }}" style="text-align:center; padding:40px; color:#9ca3af;">
                    <i class="ti ti-wallet" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                    Belum ada data kas {{ strtolower($title) }}.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="pag-wrap">
        <div>
            Menampilkan <b>{{ $kasList->firstItem() ?? 0 }}</b>–<b>{{ $kasList->lastItem() ?? 0 }}</b>
            dari <b>{{ $kasList->total() }}</b> data
        </div>
        <div style="display:flex; gap:6px;">
            @if($kasList->onFirstPage())
                <span class="pag-btn disabled">← Previous</span>
            @else
                <a href="{{ $kasList->previousPageUrl() }}" class="pag-btn">← Previous</a>
            @endif
            @if($kasList->hasMorePages())
                <a href="{{ $kasList->nextPageUrl() }}" class="pag-btn">Next →</a>
            @else
                <span class="pag-btn disabled">Next →</span>
            @endif
        </div>
    </div>
</div>

{{-- Form Modal --}}
<div class="custom-modal" id="kasModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <span id="modalTitle">Tambah Kas {{ $title }}</span>
            <button class="icon-btn" onclick="closeModal()"><i class="ti ti-x"></i></button>
        </div>
        <form id="formKas" onsubmit="saveKas(event)" x-data="initKasForm()">
            <div class="custom-modal-body">
                <input type="hidden" id="kas_id">
                <input type="hidden" id="kas_jenis" value="{{ $jenis }}">

                @if($authUser->role === 'super_admin')
                <div class="form-group">
                    <label>Fakultas</label>
                    <div class="search-select" x-data="searchSelect({
                        items: @js($fakultasList->map(fn($f) => ['id' => $f->id, 'label' => $f->nama_fakultas, 'sub' => ''])),
                        inputId: 'kas_fakultas_id',
                        placeholder: 'Cari fakultas...'
                    })" @click.outside="close()">
                        <input type="hidden" id="kas_fakultas_id" :value="selectedId">
                        <input type="text" class="search-select-input" :placeholder="placeholder"
                            :value="displayValue" @focus="open()" @input="query = $event.target.value; open()">
                        <i class="ti ti-chevron-down search-select-chevron" @click.stop="toggle()"></i>
                        <div class="search-select-dropdown" x-show="isOpen" x-transition x-cloak>
                            <template x-for="item in filtered" :key="item.id">
                                <div class="search-select-option"
                                    :class="{ selected: selectedId == item.id }"
                                    @click="select(item)">
                                    <span x-text="item.label"></span>
                                </div>
                            </template>
                            <div class="search-select-option" @click="select({id:'',label:''})" style="color:#94a3b8;">— Semua Fakultas —</div>
                            <div class="search-select-empty" x-show="filtered.length === 0">Tidak ditemukan</div>
                        </div>
                    </div>
                </div>
                @endif

                @if($jenis === 'masuk')
                <div class="form-group">
                    <label>Nama Dosen (Opsional)</label>
                    <div class="search-select" x-data="searchSelect({
                        items: @js($dosensList->map(fn($d) => [
                            'id' => $d->id, 
                            'label' => $d->nama_lengkap, 
                            'sub' => ($d->prodi->nama_prodi ?? '-') . ' (' . ($d->prodi->fakultas->nama_fakultas ?? '-') . ')',
                            'fakultas_id' => $d->prodi->fakultas_id ?? null
                        ])),
                        inputId: 'kas_dosen_id',
                        placeholder: 'Cari nama dosen...'
                    })" @click.outside="close()">
                        <input type="hidden" id="kas_dosen_id" :value="selectedId">
                        <input type="text" class="search-select-input" :placeholder="placeholder"
                            :value="displayValue" @focus="open()" @input="query = $event.target.value; open()">
                        <i class="ti ti-chevron-down search-select-chevron" @click.stop="toggle()"></i>
                        <div class="search-select-dropdown" x-show="isOpen" x-transition x-cloak>
                            <template x-for="item in filtered" :key="item.id">
                                <div class="search-select-option"
                                    :class="{ selected: selectedId == item.id }"
                                    @click="select(item)">
                                    <span x-text="item.label"></span>
                                    <small x-text="item.sub"></small>
                                </div>
                            </template>
                            <div class="search-select-option" @click="select({id:'',label:''})" style="color:#94a3b8;">— Tanpa Dosen —</div>
                            <div class="search-select-empty" x-show="filtered.length === 0">Tidak ditemukan</div>
                        </div>
                    </div>
                </div>
                @elseif($jenis === 'keluar')
                <div class="form-group">
                    <label>Kategori Pengeluaran</label>
                    <select id="kas_kategori" class="filter-control" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="biaya_operasional">Biaya Operasional</option>
                        <option value="kegiatan_kunjungan_industri">Kegiatan Kunjungan Industri</option>
                        <option value="pembelian_perlengkapan">Pembelian Perlengkapan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                @endif

                <div class="form-group">
                    <label>Jumlah ({{ $jenis === 'masuk' ? 'Rp' : 'Rp' }})</label>
                    <input type="number" id="kas_jumlah" class="filter-control" min="1" step="1" required placeholder="Contoh: 60000" x-model="jumlah" @input="hitung()">
                    
                    @if($jenis === 'masuk')
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
                        <div style="display:flex; justify-content:space-between; border-top:1px solid #bfdbfe; padding-top:6px; margin-top:6px;">
                            <span style="font-weight:600; color:#374151;">Total</span>
                            <span style="font-family:monospace; font-weight:700;">Rp <span x-text="formatRp(jumlah)"></span></span>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" id="kas_tanggal" class="filter-control" required value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" id="kas_keterangan" class="filter-control" required placeholder="cth: Iuran kas bulan Mei 2026">
                </div>


                @if($jenis === 'keluar')
                <div class="form-group" style="margin-top:16px;">
                    <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:6px">
                        Bukti Foto / Struk Pembelian <span style="color:#6b7280; font-weight:400">(opsional)</span>
                    </label>

                    <!-- Drop zone / input file -->
                    <div id="dropzone" style="border:2px dashed #d1d5db; border-radius:8px; padding:20px; text-align:center; cursor:pointer; background:#f9fafb" onclick="document.getElementById('kas_bukti_foto').click()">
                        <i class="ti ti-photo-up" style="font-size:28px;color:#9ca3af"></i>
                        <p style="font-size:12px; color:#6b7280; margin-top:6px">
                            Klik atau drag foto ke sini<br>
                            <span style="font-size:11px">JPG, PNG, WEBP — Maks 2MB</span>
                        </p>
                    </div>

                    <input type="file" id="kas_bukti_foto" accept="image/png, image/jpeg, image/jpg, image/webp" style="display:none" onchange="previewFoto(this)">
                    
                    <!-- Preview foto yang dipilih -->
                    <div id="previewContainer" style="display:flex; flex-wrap:wrap; gap:8px; margin-top:10px"></div>
                </div>
                @endif
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Detail Modal --}}
<div class="custom-modal" id="detailModal">
    <div class="detail-modal-content">
        <div class="custom-modal-header">
            <span id="detailTitle">Detail Kas</span>
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const jenis = "{{ $jenis }}";

    function searchSelect(config) {
        return {
            items: config.items || [],
            inputId: config.inputId || '',
            placeholder: config.placeholder || 'Cari...',
            query: '',
            isOpen: false,
            selectedId: '',
            selectedLabel: '',
            // Properti reaktif untuk menyimpan fakultas yang dipilih (khusus dosen)
            activeFakultasId: '',

            init() {
                // Watch selectedId to keep selectedLabel synced
                this.$watch('selectedId', (val) => {
                    const match = this.items.find(i => i.id == val);
                    this.selectedLabel = match ? match.label : '';
                });

                // Check initial value from DOM
                const hiddenEl = document.getElementById(this.inputId);
                if (hiddenEl) {
                    if (hiddenEl.value) {
                        this.selectedId = hiddenEl.value;
                    }
                    // Listen to external programmatical resets (e.g. editKas or openModal)
                    hiddenEl.addEventListener('change-value', (e) => {
                        this.selectedId = e.detail;
                    });
                }

                // Jika ini adalah select Dosen, listen ke perubahan Fakultas
                if (this.inputId === 'kas_dosen_id') {
                    const fakInput = document.getElementById('kas_fakultas_id');
                    if (fakInput) {
                        // Set nilai awal
                        this.activeFakultasId = fakInput.value || '';

                        // Update activeFakultasId tiap kali fakultas berubah (reaktif!)
                        fakInput.addEventListener('change', () => {
                            this.activeFakultasId = fakInput.value || '';

                            // Auto-reset dosen jika tidak cocok dengan fakultas baru
                            const currentDosen = this.items.find(i => i.id == this.selectedId);
                            if (currentDosen && fakInput.value && currentDosen.fakultas_id != fakInput.value) {
                                this.selectedId = '';
                                this.selectedLabel = '';
                                const dosenHidden = document.getElementById(this.inputId);
                                if (dosenHidden) {
                                    dosenHidden.value = '';
                                    dosenHidden.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            }
                        });

                        // Listen juga ke 'change-value' event dari Alpine (saat editKas)
                        fakInput.addEventListener('change-value', (e) => {
                            this.activeFakultasId = e.detail || '';
                        });
                    }
                }
            },

            get filtered() {
                let filteredItems = this.items;

                // Filter berdasarkan activeFakultasId (properti reaktif Alpine.js)
                if (this.inputId === 'kas_dosen_id' && this.activeFakultasId) {
                    filteredItems = this.items.filter(item => item.fakultas_id == this.activeFakultasId);
                }

                if (!this.query) return filteredItems;
                const q = this.query.toLowerCase();
                return filteredItems.filter(item =>
                    item.label.toLowerCase().includes(q) ||
                    (item.sub && item.sub.toLowerCase().includes(q))
                );
            },

            open() {
                this.isOpen = true;
                this.query = '';
            },

            close() {
                this.isOpen = false;
                this.query = '';
            },

            toggle() {
                if (this.isOpen) {
                    this.close();
                } else {
                    this.open();
                }
            },

            select(item) {
                this.selectedId = item.id;
                this.selectedLabel = item.label;
                this.close();

                // Sync ke hidden input
                const hiddenEl = document.getElementById(this.inputId);
                if (hiddenEl) {
                    hiddenEl.value = item.id;
                    hiddenEl.dispatchEvent(new Event('change', { bubbles: true }));
                }

                // Jika dosen dipilih, auto-set Fakultas sesuai dosen tersebut
                if (this.inputId === 'kas_dosen_id' && item.fakultas_id) {
                    const fakInput = document.getElementById('kas_fakultas_id');
                    if (fakInput && fakInput.value != item.fakultas_id) {
                        fakInput.value = item.fakultas_id;
                        // Update activeFakultasId di komponen Fakultas via custom event
                        fakInput.dispatchEvent(new CustomEvent('change-value', { detail: item.fakultas_id }));
                        fakInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    // Update juga activeFakultasId lokal agar tetap sinkron
                    this.activeFakultasId = String(item.fakultas_id);
                }
            },

            get displayValue() {
                if (this.isOpen) {
                    return this.query;
                }
                return this.selectedLabel || '';
            }
        }
    }

    function showToast(msg, type = 'success') {
        const t = document.createElement('div');
        t.className = `custom-toast ${type === 'error' ? 'error' : ''}`;
        t.innerHTML = `<i class="ti ti-${type === 'success' ? 'check' : 'alert-circle'}"></i> ${msg}`;
        document.getElementById('toast-container').appendChild(t);
        setTimeout(() => t.remove(), 3500);
    }



    function openModal() {
        document.getElementById('formKas').reset();
        document.getElementById('kas_id').value = '';
        document.getElementById('modalTitle').textContent = 'Tambah Kas {{ $title }}';
        document.getElementById('kas_tanggal').value = new Date().toISOString().split('T')[0];
        const preview = document.getElementById('previewContainer');
        if (preview) preview.innerHTML = '';
        
        // Reset searchable select hidden inputs
        const fakSel = document.getElementById('kas_fakultas_id');
        if (fakSel) {
            fakSel.value = '';
            fakSel.dispatchEvent(new CustomEvent('change-value', { detail: '' }));
        }
        const dosenSel = document.getElementById('kas_dosen_id');
        if (dosenSel) {
            dosenSel.value = '';
            dosenSel.dispatchEvent(new CustomEvent('change-value', { detail: '' }));
        }

        document.getElementById('kasModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('kasModal').classList.remove('active');
    }

    function closeDetail() {
        document.getElementById('detailModal').classList.remove('active');
    }

    /* ── Foto Preview ──────────────────────────────── */
    function previewFoto(input) {
        const container = document.getElementById('previewContainer');
        container.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 2 * 1024 * 1024) {
                showToast(file.name + ' melebihi 2MB!', 'error');
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
        const input = document.getElementById('kas_bukti_foto');
        if (input) input.value = '';
        const container = document.getElementById('previewContainer');
        if (container) container.innerHTML = '';
    }

    async function viewDetail(id) {
        try {
            const res = await fetch(`/kas/transaksi/${id}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();

            const fakNama = data.fakultas ? data.fakultas.nama_fakultas : '-';

            document.getElementById('detailTitle').textContent = 'Detail Kas';
            document.getElementById('detailBody').innerHTML = `
                <div class="detail-section">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <div class="detail-label">Tanggal</div>
                            <div class="detail-value">${new Date(data.tanggal).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'})}</div>
                        </div>
                        <div>
                            <div class="detail-label">Jenis</div>
                            <div class="detail-value">${data.jenis === 'masuk' ? 'Kas Masuk' : 'Kas Keluar'}</div>
                        </div>
                    </div>
                </div>
                <div class="detail-section">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <div class="detail-label">Jumlah</div>
                            <div class="detail-value" style="font-weight:600; font-size:16px; color:${data.jenis === 'masuk' ? '#059669' : '#dc2626'}">
                                ${data.jenis === 'masuk' ? '+' : '-'} Rp ${parseInt(data.jumlah).toLocaleString('id-ID')}
                            </div>
                        </div>
                        <div>
                            <div class="detail-label">Fakultas</div>
                            <div class="detail-value">${fakNama}</div>
                        </div>
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Keterangan</div>
                    <div class="detail-value" style="background:#f8fafc; padding:10px 14px; border-radius:8px; margin-top:6px;">${data.keterangan ?? '-'}</div>
                </div>



                ${data.jenis === 'keluar' && data.bukti_foto ? `
                <div class="detail-section">
                    <div class="detail-label">Bukti Foto / Struk</div>
                    <div class="detail-value" style="margin-top:6px; background:#f8fafc; padding:10px; border-radius:8px; text-align:center;">
                        <a href="/storage/${data.bukti_foto}" target="_blank">
                            <img src="/storage/${data.bukti_foto}" alt="Bukti Foto" style="max-width:100%; max-height:200px; border-radius:6px; border:1px solid #e2e8f0; object-fit:contain;">
                        </a>
                        <div style="font-size:11px; color:#64748b; margin-top:6px;">Klik gambar untuk memperbesar</div>
                    </div>
                </div>
                ` : ''}

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

    async function editKas(id) {
        try {
            const res = await fetch(`/kas/transaksi/${id}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();

            document.getElementById('kas_id').value = data.id;
            document.getElementById('kas_jumlah').value = data.jumlah;
            document.getElementById('kas_tanggal').value = data.tanggal;
            document.getElementById('kas_keterangan').value = data.keterangan;

            const fakSel = document.getElementById('kas_fakultas_id');
            if (fakSel) {
                fakSel.value = data.fakultas_id || '';
                fakSel.dispatchEvent(new CustomEvent('change-value', { detail: data.fakultas_id || '' }));
            }

            const dosenSel = document.getElementById('kas_dosen_id');
            if (dosenSel) {
                dosenSel.value = data.dosen_id || '';
                dosenSel.dispatchEvent(new CustomEvent('change-value', { detail: data.dosen_id || '' }));
            }

            const katSel = document.getElementById('kas_kategori');
            if (katSel) katSel.value = data.kategori || '';

            const fotoInput = document.getElementById('kas_bukti_foto');
            if (fotoInput) fotoInput.value = ''; // Clear previous file input
            const preview = document.getElementById('previewContainer');
            if (preview) preview.innerHTML = ''; // Clear preview

            document.getElementById('modalTitle').textContent = 'Edit Kas {{ $title }}';
            document.getElementById('kasModal').classList.add('active');



            // Trigger Alpine x-model
            setTimeout(() => {
                document.getElementById('kas_jumlah').dispatchEvent(new Event('input'));
            }, 100);
        } catch {
            showToast('Gagal memuat data.', 'error');
        }
    }

    async function saveKas(e) {
        e.preventDefault();

        const id = document.getElementById('kas_id').value;
        const url = id ? `/kas/transaksi/${id}` : `/kas/transaksi`;

        const formData = new FormData();
        formData.append('jenis', document.getElementById('kas_jenis').value);
        formData.append('jumlah', document.getElementById('kas_jumlah').value);
        formData.append('tanggal', document.getElementById('kas_tanggal').value);
        formData.append('keterangan', document.getElementById('kas_keterangan').value);

        const fakSel = document.getElementById('kas_fakultas_id');
        if (fakSel && fakSel.value) formData.append('fakultas_id', fakSel.value);

        const dosenSel = document.getElementById('kas_dosen_id');
        if (dosenSel && dosenSel.value) formData.append('dosen_id', dosenSel.value);

        const katSel = document.getElementById('kas_kategori');
        if (katSel && katSel.value) formData.append('kategori', katSel.value);

        const fotoInput = document.getElementById('kas_bukti_foto');
        if (fotoInput && fotoInput.files[0]) {
            formData.append('bukti_foto', fotoInput.files[0]);
        }

        if (id) formData.append('_method', 'PUT');

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData,
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message, 'success');
                closeModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || data.errors ? Object.values(data.errors || {}).flat().join(' ') : 'Terjadi kesalahan.', 'error');
            }
        } catch {
            showToast('Koneksi gagal.', 'error');
        }
    }

    function initKasForm() {
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

    async function deleteKas(id) {
        if (!confirm('Yakin ingin menghapus data kas ini?')) return;
        try {
            const res = await fetch(`/kas/transaksi/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ _method: 'DELETE' }),
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Gagal menghapus.', 'error');
            }
        } catch {
            showToast('Koneksi gagal.', 'error');
        }
    }
</script>
@endpush