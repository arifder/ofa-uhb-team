

@extends('layouts.dashboard')
@section('title', 'Notulensi Rapat')

@push('styles')
<style>
    /* ── Base Card & Table ─────────────────────────── */
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

    /* ── Badges ────────────────────────────────────── */
    .fak-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .fak-fst  { background: #dbeafe; color: #1d4ed8; }
    .fak-fis  { background: #fef3c7; color: #92400e; }
    .fak-other{ background: #e2e8f0; color: #475569; }

    /* ── Buttons ───────────────────────────────────── */
    .btn-primary { background: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-outline { background: #fff; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; }
    .btn-outline:hover { background: #f1f5f9; }
    .icon-btn { background: none; border: none; cursor: pointer; color: #64748b; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; }
    .icon-btn:hover { background: #f1f5f9; color: #2563eb; }
    .icon-btn.delete:hover { color: #ef4444; }

    /* ── Filter ────────────────────────────────────── */
    .filter-control { border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; font-size: 13px; outline: none; width: 100%; }
    .filter-control:focus { border-color: #2563eb; }

    /* ── Modal ─────────────────────────────────────── */
    .custom-modal { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.5); align-items: center; justify-content: center; z-index: 50; }
    .custom-modal.active { display: flex; }
    .custom-modal-content { background: #fff; width: 560px; max-height: 90vh; overflow-y: auto; border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,.15); font-family: 'Plus Jakarta Sans', sans-serif; }
    .custom-modal-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; font-weight: 600; font-size: 16px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: #fff; z-index: 1; }
    .custom-modal-body { padding: 22px; font-size: 13px; }
    .custom-modal-footer { padding: 16px 22px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; border-radius: 0 0 14px 14px; position: sticky; bottom: 0; }
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 6px; color: #475569; }

    /* ── Detail Modal ──────────────────────────────── */
    .detail-modal-content { background: #fff; width: 600px; max-height: 90vh; overflow-y: auto; border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,.15); }
    .detail-section { padding: 16px 22px; border-bottom: 1px solid #f1f5f9; }
    .detail-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .detail-value { font-size: 13px; color: #1e293b; white-space: pre-line; }
    .peserta-chip { display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 20px; font-size: 12px; color: #475569; margin: 3px; }

    /* ── Peserta Checkbox List ──────────────────────── */
    .peserta-list { max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px; }
    .peserta-item { display: flex; align-items: center; gap: 8px; padding: 5px 8px; border-radius: 6px; cursor: pointer; }
    .peserta-item:hover { background: #f8fafc; }
    .peserta-item input[type=checkbox] { width: 15px; height: 15px; accent-color: #2563eb; cursor: pointer; }

    /* ── Pagination ────────────────────────────────── */
    .pag-wrap { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b; }
    .pag-btn { padding: 6px 14px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; color: #475569; background: #fff; text-decoration: none; }
    .pag-btn:hover { background: #f1f5f9; }
    .pag-btn.disabled { opacity: .45; pointer-events: none; }

    /* ── Toast ─────────────────────────────────────── */
    #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
    .custom-toast { min-width: 260px; background: #fff; border-left: 4px solid #10b981; box-shadow: 0 4px 12px rgba(0,0,0,.1); padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideIn .3s ease forwards; }
    .custom-toast.error { border-left-color: #ef4444; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>
@endpush

@section('content')

@php $authUser = auth()->user(); @endphp

{{-- Page Header --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:18px; font-weight:600; color:#1e293b;">
        Notulensi Rapat
        <span style="font-size:13px; font-weight:400; color:#64748b; margin-left:8px;">Total: {{ $notulensiList->total() }}</span>
    </h2>
    @if(!in_array($authUser->role, ['kepala_unit', 'dosen']))
    <button class="btn-primary" onclick="openModal()">
        <i class="ti ti-plus"></i> Tambah Notulensi
    </button>
    @endif
</div>

<div id="toast-container"></div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('notulensi.index') }}" class="master-card p-4 mb-4" style="padding:16px;">
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <input type="text" name="search" class="filter-control" placeholder="Cari judul notulensi..." value="{{ request('search') }}" style="flex:1; min-width:200px;">
        @if(in_array($authUser->role, ['super_admin', 'kepala_unit']))
        <select name="fakultas_id" class="filter-control" style="width:200px;" onchange="this.form.submit()">
            <option value="">Semua Fakultas</option>
            @foreach($fakultasList as $fak)
                <option value="{{ $fak->id }}" {{ request('fakultas_id') == $fak->id ? 'selected' : '' }}>
                    {{ $fak->nama_fakultas }}
                </option>
            @endforeach
        </select>
        @endif
        <button type="submit" class="btn-outline">Filter</button>
        <a href="{{ route('notulensi.index') }}" class="btn-outline">Reset</a>
    </div>
</form>

{{-- Table --}}
<div class="master-card">
    <div class="table-responsive">
        <table class="master-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Tempat</th>
                    <th>Fakultas</th>
                    <th>Peserta</th>
                    <th>Dibuat Oleh</th>
                    @if(!in_array($authUser->role, ['kepala_unit', 'dosen']))
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($notulensiList as $index => $not)
                <tr>
                    <td>{{ $notulensiList->firstItem() + $index }}</td>
                    <td>
                        <a href="#" onclick="viewDetail({{ $not->id }})" style="color:#2563eb; font-weight:500; text-decoration:none;">
                            {{ $not->judul }}
                        </a>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($not->tanggal)->translatedFormat('d M Y') }}</td>
                    <td>{{ $not->tempat }}</td>
                    <td>
                        @php
                            $fakNama = $not->fakultas->nama_fakultas ?? '-';
                            $fakClass = str_contains(strtolower($fakNama), 'sains') ? 'fak-fst'
                                      : (str_contains(strtolower($fakNama), 'sosial') ? 'fak-fis' : 'fak-other');
                        @endphp
                        <span class="fak-badge {{ $fakClass }}">{{ $fakNama }}</span>
                    </td>
                    <td>
                        <span style="font-size:12px; color:#64748b;">
                            {{ $not->dosens->count() }} dosen
                        </span>
                    </td>
                    <td style="color:#64748b; font-size:12px;">{{ $not->user->name ?? '-' }}</td>
                    @if(!in_array($authUser->role, ['kepala_unit', 'dosen']))
                    <td>
                        <button class="icon-btn" onclick="viewDetail({{ $not->id }})" title="Lihat Detail">
                            <i class="ti ti-eye"></i>
                        </button>
                        <button class="icon-btn" onclick="openExportModal({{ $not->id }}, {{ $not->fakultas_id ?? 'null' }}, {{ $not->dosens->first()?->prodi_id ?? 'null' }})" style="color:#0f766e" title="Export BAP">
                            <i class="ti ti-printer"></i>
                        </button>
                        <a href="{{ route('notulensi.exportPdf', $not->id) }}" class="icon-btn" style="color:#2563eb" title="Export Notulensi (PDF)">
                            <i class="ti ti-file-type-pdf"></i>
                        </a>
                        <button class="icon-btn" onclick="editNotulensi({{ $not->id }})" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </button>
                        <button class="icon-btn delete" onclick="deleteNotulensi({{ $not->id }})" title="Hapus">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                    @else
                    <td>
                        <button class="icon-btn" onclick="viewDetail({{ $not->id }})" title="Lihat Detail">
                            <i class="ti ti-eye"></i>
                        </button>
                        <button class="icon-btn" onclick="openExportModal({{ $not->id }}, {{ $not->fakultas_id ?? 'null' }}, {{ $not->dosens->first()?->prodi_id ?? 'null' }})" style="color:#0f766e" title="Export BAP">
                            <i class="ti ti-printer"></i>
                        </button>
                        <a href="{{ route('notulensi.exportPdf', $not->id) }}" class="icon-btn" style="color:#2563eb" title="Export Notulensi (PDF)">
                            <i class="ti ti-file-type-pdf"></i>
                        </a>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:40px; color:#9ca3af;">
                        <i class="ti ti-clipboard-off" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                        Belum ada data notulensi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pag-wrap">
        <div>
            Menampilkan <b>{{ $notulensiList->firstItem() ?? 0 }}</b>–<b>{{ $notulensiList->lastItem() ?? 0 }}</b>
            dari <b>{{ $notulensiList->total() }}</b> notulensi
        </div>
        <div style="display:flex; gap:6px;">
            @if($notulensiList->onFirstPage())
                <span class="pag-btn disabled">← Previous</span>
            @else
                <a href="{{ $notulensiList->previousPageUrl() }}" class="pag-btn">← Previous</a>
            @endif
            @if($notulensiList->hasMorePages())
                <a href="{{ $notulensiList->nextPageUrl() }}" class="pag-btn">Next →</a>
            @else
                <span class="pag-btn disabled">Next →</span>
            @endif
        </div>
    </div>
</div>

{{-- ======================================================= --}}
{{-- FORM MODAL (Tambah / Edit)                               --}}
{{-- ======================================================= --}}
<div class="custom-modal" id="notulensiModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <span id="modalTitle">Tambah Notulensi</span>
            <button class="icon-btn" onclick="closeModal()"><i class="ti ti-x"></i></button>
        </div>
        <form id="notulensiForm" onsubmit="saveNotulensi(event)">
            <div class="custom-modal-body">
                <input type="hidden" id="not_id">

                {{-- Fakultas (super_admin only) --}}
                @if($authUser->role === 'super_admin')
                <div class="form-group">
                    <label>Fakultas</label>
                    <select id="not_fakultas_id" class="filter-control" required>
                        <option value="">Pilih Fakultas</option>
                        @foreach($fakultasList as $fak)
                            <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="form-group">
                    <label>Judul Rapat</label>
                    <input type="text" id="not_judul" class="filter-control" required placeholder="Cth: Rapat Rutin Mei 2026">
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" id="not_tanggal" class="filter-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tempat</label>
                        <input type="text" id="not_tempat" class="filter-control" required placeholder="Cth: Ruang Rapat A">
                    </div>
                </div>
                <div class="form-group">
                    <label>Agenda Rapat</label>
                    <textarea id="not_agenda_rapat" class="filter-control" rows="3" placeholder="Tuliskan agenda/poin-poin rapat..." style="resize:vertical;"></textarea>
                </div>
                <div class="form-group">
                    <label>Resume Rapat</label>
                    <textarea id="not_agenda" class="filter-control" rows="8" placeholder="Tuliskan resume notulensi rapat di sini..."></textarea>
                </div>
                <div class="form-group">
                    <label>Tindak Lanjut <span style="color:#94a3b8;">(opsional)</span></label>
                    <textarea id="not_tindak_lanjut" class="filter-control" rows="2" placeholder="Catatan tindak lanjut..." style="resize:vertical;"></textarea>
                </div>
                <div class="form-group">
                    <label>Peserta Rapat <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="searchPeserta" class="filter-control" placeholder="Cari dosen..." onkeyup="filterPeserta()" style="margin-bottom:8px;">
                    <div class="peserta-list" id="pesertaList">
                        @foreach($dosenList as $dosen)
                        <label class="peserta-item" data-nama="{{ strtolower($dosen->nama_lengkap) }}">
                            <input type="checkbox" class="peserta-check" value="{{ $dosen->id }}">
                            <span>
                                <b>{{ $dosen->nama_lengkap }}</b>
                                <span style="color:#94a3b8; font-size:11px;"> — {{ $dosen->prodi->nama_prodi ?? '-' }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                    <div id="pesertaError" style="color:#ef4444; font-size:11px; margin-top:4px; display:none;">Pilih minimal 1 peserta.</div>
                </div>

                <!-- DOKUMENTASI -->
                <div style="margin-top:16px">
                    <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:6px">
                        Dokumentasi Foto <span style="color:#6b7280; font-weight:400">(opsional, bisa lebih dari 1)</span>
                    </label>

                    <!-- Foto existing saat edit -->
                    <div id="existingPhotosContainer" style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:8px"></div>

                    <!-- Drop zone / input file -->
                    <div id="dropzone" style="border:2px dashed #d1d5db; border-radius:8px; padding:20px; text-align:center; cursor:pointer; background:#f9fafb" onclick="document.getElementById('inputDokumentasi').click()">
                        <i class="ti ti-photo-up" style="font-size:28px;color:#9ca3af"></i>
                        <p style="font-size:12px; color:#6b7280; margin-top:6px">
                            Klik atau drag foto ke sini<br>
                            <span style="font-size:11px">JPG, PNG — Maks 5MB per file</span>
                        </p>
                    </div>

                    <input type="file" id="inputDokumentasi" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png" style="display:none" onchange="tambahFoto(this)"/>

                    <!-- Preview foto baru yang dipilih -->
                    <div id="previewContainer" style="display:flex; flex-wrap:wrap; gap:8px; margin-top:10px"></div>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================= --}}
{{-- DETAIL MODAL                                             --}}
{{-- ======================================================= --}}
<div class="custom-modal" id="detailModal">
    <div class="detail-modal-content">
        <div class="custom-modal-header">
            <span id="detailTitle">Detail Notulensi</span>
            <button class="icon-btn" onclick="closeDetail()"><i class="ti ti-x"></i></button>
        </div>
        <div id="detailBody" style="padding:0;">
            {{-- Filled by JS --}}
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn-outline" onclick="closeDetail()">Tutup</button>
        </div>
    </div>
</div>

{{-- ======================================================= --}}
{{-- EXPORT MODAL                                             --}}
{{-- ======================================================= --}}
<div class="custom-modal" id="exportModal">
    <div class="custom-modal-content" style="width:400px;">
        <div class="custom-modal-header">
            <span>Export BAP</span>
            <button class="icon-btn" onclick="closeExportModal()"><i class="ti ti-x"></i></button>
        </div>
        <form id="exportForm" onsubmit="submitExport(event)">
            <div class="custom-modal-body">
                <input type="hidden" id="export_not_id">
                <input type="hidden" id="export_fakultas_id">
                <input type="hidden" id="export_prodi_id">
                <p style="margin-bottom:16px; color:#475569;">Pilih opsi untuk dokumen BAP yang akan dicetak:</p>
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" id="export_show_ttd" checked style="width:16px; height:16px; accent-color:#2563eb;" onchange="toggleExportNames(this)">
                    <span style="font-weight:500; color:#1e293b;">Sertakan Tanda Tangan</span>
                </label>
                <div id="exportNames" style="margin-top:16px; display:flex; flex-direction:column; gap:12px;">
                    <label>
                        Jenis Rapat / Pihak Mengetahui
                        <select id="export_jabatan" class="filter-control" style="width:100%; margin-top:4px;" onchange="handleJabatanChange()">
                            <option value="">-- Pilih --</option>
                            <option value="BAAK">Universitas (BAAK)</option>
                            <option value="Dekan">Fakultas (Dekan)</option>
                            <option value="Kaprodi">Program Studi (Kaprodi)</option>
                            <option value="Kemahasiswaan">Kemahasiswaan</option>
                            <option value="LPPM">LPPM</option>
                            <option value="Lainnya">Lainnya (Manual)</option>
                        </select>
                    </label>
                    <div id="export_nama_container" style="display:none;">
                        <label id="export_nama_label">Nama Pejabat</label>
                        <input type="text" id="export_nama" class="filter-control" placeholder="Nama Pejabat..." style="width:100%; margin-top:4px;" />
                        <div id="export_nama_info" style="font-size:11px; color:#10b981; margin-top:4px; display:none;">
                            <i class="ti ti-check"></i> Diambil otomatis dari data pejabat aktif
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-outline" onclick="closeExportModal()">Batal</button>
                <button type="submit" class="btn-primary"><i class="ti ti-download"></i> Export BAP</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    /* ── Toast ─────────────────────────────────────── */
    function showToast(msg, type = 'success') {
        const t = document.createElement('div');
        t.className = `custom-toast ${type === 'error' ? 'error' : ''}`;
        t.innerHTML = `<i class="ti ti-${type === 'success' ? 'check' : 'alert-circle'}"></i> ${msg}`;
        document.getElementById('toast-container').appendChild(t);
        setTimeout(() => t.remove(), 3500);
    }

    /* ── State foto baru (accumulative) ────────────── */
    let newFotoFiles = [];
    let deletedFotoIds = [];

    /* ── Modal Open/Close ──────────────────────────── */
    function openModal() {
        document.getElementById('notulensiForm').reset();
        document.getElementById('not_id').value = '';
        document.getElementById('modalTitle').textContent = 'Tambah Notulensi';
        document.querySelectorAll('.peserta-check').forEach(c => c.checked = false);
        document.getElementById('pesertaError').style.display = 'none';
        document.getElementById('inputDokumentasi').value = '';
        document.getElementById('previewContainer').innerHTML = '';
        document.getElementById('existingPhotosContainer').innerHTML = '';
        if (tinymce.get('not_agenda')) {
            tinymce.get('not_agenda').setContent('');
        }
        document.getElementById('not_agenda_rapat').value = '';
        newFotoFiles = [];
        deletedFotoIds = [];
        document.getElementById('notulensiModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('notulensiModal').classList.remove('active');
    }

    function closeDetail() {
        document.getElementById('detailModal').classList.remove('active');
    }

    const pejabatList = @json($pejabatList ?? []);
    const pejabatFakultas = @json($pejabatFakultas ?? []);
    const pejabatProdi = @json($pejabatProdi ?? []);

    /* ── Export Modal ──────────────────────────────── */
    function openExportModal(id, fakultasId = null, prodiId = null) {
        document.getElementById('export_not_id').value = id;
        document.getElementById('export_fakultas_id').value = fakultasId;
        document.getElementById('export_prodi_id').value = prodiId;
        const showTtdCheckbox = document.getElementById('export_show_ttd');
        showTtdCheckbox.checked = true;
        document.getElementById('export_jabatan').value = '';
        document.getElementById('export_nama').value = '';
        document.getElementById('export_nama').readOnly = false;
        toggleExportNames(showTtdCheckbox);
        document.getElementById('exportModal').classList.add('active');
    }

    function toggleExportNames(el) {
        const nameDiv = document.getElementById('exportNames');
        if (el.checked) {
            nameDiv.style.display = 'flex';
        } else {
            nameDiv.style.display = 'none';
            document.getElementById('export_jabatan').value = '';
            document.getElementById('export_nama').value = '';
            document.getElementById('export_nama_container').style.display = 'none';
        }
    }

    function handleJabatanChange() {
        const jabatan = document.getElementById('export_jabatan').value;
        const container = document.getElementById('export_nama_container');
        const input = document.getElementById('export_nama');
        const label = document.getElementById('export_nama_label');
        const info = document.getElementById('export_nama_info');
        const fakultasId = document.getElementById('export_fakultas_id').value;
        const prodiId = document.getElementById('export_prodi_id').value;

        if (!jabatan) {
            container.style.display = 'none';
            input.value = '';
            return;
        }

        container.style.display = 'block';
        info.style.display = 'none';
        input.readOnly = false;
        input.value = '';
        input.placeholder = 'Ketik nama pejabat...';

        if (jabatan === 'Lainnya') {
            label.textContent = 'Nama Pejabat';
            input.placeholder = 'Ketik nama pejabat...';
            input.readOnly = false;
            return;
        }

        // Set label sesuai jabatan
        const labelMap = {
            'BAAK': 'Nama Kepala BAAK',
            'Dekan': 'Nama Dekan',
            'Kaprodi': 'Nama Kaprodi',
            'Kemahasiswaan': 'Nama Kepala Kemahasiswaan',
            'LPPM': 'Nama Ketua LPPM'
        };
        label.textContent = labelMap[jabatan] || 'Nama Pejabat';

        let targetPejabat = null;
        let autoFilled = false;

        // 1. Cari dari pejabatList (User dengan jabatan_struktural aktif)
        if (jabatan === 'BAAK') {
            targetPejabat = pejabatList.find(p => p.jabatan_struktural === 'BAAK' && p.status === 'aktif');
        } else if (jabatan === 'Dekan') {
            targetPejabat = pejabatList.find(p => p.jabatan_struktural === 'Dekan' && p.fakultas_id == fakultasId && p.status === 'aktif');
        } else if (jabatan === 'Kaprodi') {
            targetPejabat = pejabatList.find(p => p.jabatan_struktural === 'Kaprodi' && p.prodi_id == prodiId && p.status === 'aktif');
        } else if (jabatan === 'Kemahasiswaan') {
            targetPejabat = pejabatList.find(p => p.jabatan_struktural === 'Kemahasiswaan' && p.status === 'aktif');
        } else if (jabatan === 'LPPM') {
            targetPejabat = pejabatList.find(p => p.jabatan_struktural === 'LPPM' && p.status === 'aktif');
        }

        // 2. Jika tidak ditemukan, cari dari pejabatFakultas (nama_dekan di tabel fakultas)
        if (!targetPejabat && jabatan === 'Dekan' && fakultasId && pejabatFakultas[fakultasId]) {
            input.value = pejabatFakultas[fakultasId].nama;
            input.readOnly = true;
            info.style.display = 'block';
            autoFilled = true;
        }

        // 3. Jika tidak ditemukan, cari dari pejabatProdi (nama_kaprodi di tabel prodi)
        if (!targetPejabat && !autoFilled && jabatan === 'Kaprodi' && prodiId && pejabatProdi[prodiId]) {
            input.value = pejabatProdi[prodiId].nama;
            input.readOnly = true;
            info.style.display = 'block';
            autoFilled = true;
        }

        if (targetPejabat) {
            input.value = targetPejabat.name;
            input.readOnly = true;
            info.style.display = 'block';
        } else if (!autoFilled) {
            input.placeholder = 'Data belum diset, ketik manual...';
            input.readOnly = false;
        }
    }

    function closeExportModal() {
        document.getElementById('exportModal').classList.remove('active');
    }

    function submitExport(e) {
        e.preventDefault();
        const id = document.getElementById('export_not_id').value;
        const showTtd = document.getElementById('export_show_ttd').checked ? 1 : 0;
        const jabatan = document.getElementById('export_jabatan').value;
        const nama = document.getElementById('export_nama').value;

        closeExportModal();
        let url = `/notulensi/${id}/export-bap?show_ttd=${showTtd}`;
        if (showTtd) {
            const params = new URLSearchParams();
            let labelJabatan = '';

            if (jabatan === 'BAAK') labelJabatan = 'Kepala BAAK';
            else if (jabatan === 'Dekan') labelJabatan = 'Dekan';
            else if (jabatan === 'Kaprodi') labelJabatan = 'Ketua Program Studi';
            else if (jabatan === 'Kemahasiswaan') labelJabatan = 'Kepala Kemahasiswaan';
            else if (jabatan === 'LPPM') labelJabatan = 'Ketua LPPM';
            else if (jabatan === 'Lainnya') labelJabatan = 'Pejabat';

            if (labelJabatan) params.append('jabatan_mengetahui', labelJabatan);
            if (nama) params.append('nama_mengetahui', nama);
            const query = params.toString();
            if (query) url += '&' + query;
        }
        window.location.href = url;
    }

    /* ── Peserta Search ────────────────────────────── */
    function filterPeserta() {
        const q = document.getElementById('searchPeserta').value.toLowerCase();
        document.querySelectorAll('#pesertaList .peserta-item').forEach(item => {
            item.style.display = item.dataset.nama.includes(q) ? '' : 'none';
        });
    }

    /* ── Foto Preview (accumulative) ──────────────── */
    function tambahFoto(input) {
        Array.from(input.files).forEach(file => {
            if (!file.type.match(/image\/(jpeg|png)/)) {
                showToast(file.name + ' bukan file gambar yang valid!', 'error');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                showToast(file.name + ' melebihi 5MB!', 'error');
                return;
            }
            newFotoFiles.push(file);
        });
        // Reset input agar bisa memilih file yang sama lagi
        input.value = '';
        renderNewFotoPreview();
    }

    function renderNewFotoPreview() {
        const container = document.getElementById('previewContainer');
        container.innerHTML = '';
        newFotoFiles.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.style.cssText = 'position:relative;width:80px;';
                div.innerHTML = `
                    <img src="${e.target.result}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0"/>
                    <span onclick="hapusFotoBaru(${i})" style="position:absolute;top:-6px;right:-6px;background:#ef4444;color:#fff;border-radius:50%;width:18px;height:18px;font-size:11px;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:1">×</span>
                    <p style="font-size:9px;color:#6b7280;text-align:center;margin-top:2px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">${file.name}</p>`;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function hapusFotoBaru(index) {
        newFotoFiles.splice(index, 1);
        renderNewFotoPreview();
    }

    function renderExistingPhotos(photos) {
        const container = document.getElementById('existingPhotosContainer');
        container.innerHTML = '';
        if (!photos || photos.length === 0) return;

        const label = document.createElement('p');
        label.style.cssText = 'font-size:11px;color:#64748b;width:100%;margin-bottom:4px;font-weight:500;';
        label.textContent = 'Foto tersimpan:';
        container.appendChild(label);

        photos.forEach(dok => {
            const div = document.createElement('div');
            div.style.cssText = 'position:relative;width:80px;';
            div.dataset.dokId = dok.id;
            div.innerHTML = `
                <img src="/storage/dokumentasi-notulensi/${dok.nama_file}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0"/>
                <span onclick="hapusFotoExisting(this, ${dok.id})" style="position:absolute;top:-6px;right:-6px;background:#ef4444;color:#fff;border-radius:50%;width:18px;height:18px;font-size:11px;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:1" title="Hapus foto ini">×</span>
                <p style="font-size:9px;color:#6b7280;text-align:center;margin-top:2px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">${dok.nama_file}</p>`;
            container.appendChild(div);
        });
    }

    function hapusFotoExisting(btn, dokId) {
        deletedFotoIds.push(dokId);
        const div = btn.closest('div[data-dok-id]');
        if (div) div.remove();
        // Sembunyikan label jika tidak ada foto tersimpan lagi
        const container = document.getElementById('existingPhotosContainer');
        const remaining = container.querySelectorAll('div[data-dok-id]');
        if (remaining.length === 0) container.innerHTML = '';
    }

    /* ── View Detail ───────────────────────────────── */
    async function viewDetail(id) {
        const res = await fetch(`/notulensi/${id}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        });
        const data = await res.json();

        const pesertaChips = (data.dosens || []).map(d => {
            const prodi = d.prodi ? d.prodi.nama_prodi : '-';
            const fakultas = (d.prodi && d.prodi.fakultas) ? d.prodi.fakultas.nama_fakultas : '-';
            return `<span class="peserta-chip" title="${prodi} - ${fakultas}">
                <i class="ti ti-user" style="font-size:11px;"></i> 
                ${d.nama_lengkap} 
                <span style="font-size:10px; color:#94a3b8; margin-left:4px;">(${prodi})</span>
            </span>`;
        }).join('') || '<span style="color:#94a3b8;">Belum ada peserta</span>';

        document.getElementById('detailTitle').textContent = data.judul;
        document.getElementById('detailBody').innerHTML = `
            <div class="detail-section">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <div class="detail-label">Tanggal</div>
                        <div class="detail-value">${new Date(data.tanggal).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'})}</div>
                    </div>
                    <div>
                        <div class="detail-label">Tempat</div>
                        <div class="detail-value">${data.tempat}</div>
                    </div>
                </div>
            </div>
            <div class="detail-section">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <div class="detail-label">Fakultas</div>
                        <div class="detail-value">${data.fakultas?.nama_fakultas ?? '-'}</div>
                    </div>
                    <div>
                        <div class="detail-label">Dibuat Oleh</div>
                        <div class="detail-value">${data.user?.name ?? '-'}</div>
                    </div>
                </div>
            </div>
            ${data.agenda_rapat ? `
            <div class="detail-section">
                <div class="detail-label">Agenda Rapat</div>
                <div class="detail-value" style="background:#f8fafc; padding:10px 14px; border-radius:8px; margin-top:6px; white-space:pre-line;">${data.agenda_rapat}</div>
            </div>` : ''}
            <div class="detail-section">
                <div class="detail-label">Resume Rapat</div>
                <div class="detail-value tinymce-content" style="background:#f8fafc; padding:10px 14px; border-radius:8px; margin-top:6px;">${data.agenda}</div>
            </div>
            ${data.tindak_lanjut ? `
            <div class="detail-section">
                <div class="detail-label">Tindak Lanjut</div>
                <div class="detail-value" style="background:#f8fafc; padding:10px 14px; border-radius:8px; margin-top:6px;">${data.tindak_lanjut}</div>
            </div>` : ''}
            <div class="detail-section">
                <div class="detail-label">Peserta Rapat (${(data.dosens||[]).length} dosen)</div>
                <div style="margin-top:8px;">${pesertaChips}</div>
            </div>
            ${data.dokumentasi_notulensi && data.dokumentasi_notulensi.length > 0 ? `
            <div class="detail-section" style="border-bottom:none;">
                <div class="detail-label" style="margin-bottom:8px">Dokumentasi Foto</div>
                <div style="display:flex; flex-wrap:wrap; gap:8px">
                    ${data.dokumentasi_notulensi.map(dok => `
                        <a href="/storage/dokumentasi-notulensi/${dok.nama_file}" target="_blank">
                            <img src="/storage/dokumentasi-notulensi/${dok.nama_file}" style="width:100px;height:100px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; cursor:pointer"/>
                        </a>
                    `).join('')}
                </div>
            </div>
            ` : ''}
        `;
        document.getElementById('detailModal').classList.add('active');
    }

    /* ── Edit ──────────────────────────────────────── */
    async function editNotulensi(id) {
        const res = await fetch(`/notulensi/${id}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        });
        const data = await res.json();

        // Reset state foto
        newFotoFiles = [];
        deletedFotoIds = [];
        document.getElementById('previewContainer').innerHTML = '';
        document.getElementById('inputDokumentasi').value = '';

        document.getElementById('modalTitle').textContent = 'Edit Notulensi';
        document.getElementById('not_id').value = data.id;
        document.getElementById('not_judul').value = data.judul;
        document.getElementById('not_tanggal').value = data.tanggal;
        document.getElementById('not_tempat').value = data.tempat;
        if (tinymce.get('not_agenda')) {
            tinymce.get('not_agenda').setContent(data.agenda || '');
        } else {
            document.getElementById('not_agenda').value = data.agenda || '';
        }
        document.getElementById('not_tindak_lanjut').value = data.tindak_lanjut ?? '';
        document.getElementById('not_agenda_rapat').value = data.agenda_rapat ?? '';

        // Set fakultas if super_admin field exists
        const fakSel = document.getElementById('not_fakultas_id');
        if (fakSel) fakSel.value = data.fakultas_id;

        // Tick peserta checkboxes
        const pesertaIds = (data.dosens || []).map(d => String(d.id));
        document.querySelectorAll('.peserta-check').forEach(c => {
            c.checked = pesertaIds.includes(c.value);
        });

        // Tampilkan foto existing
        renderExistingPhotos(data.dokumentasi_notulensi || []);

        document.getElementById('pesertaError').style.display = 'none';
        document.getElementById('notulensiModal').classList.add('active');
    }

    /* ── Save (Store/Update) ───────────────────────── */
    async function saveNotulensi(e) {
        e.preventDefault();

        const checked = [...document.querySelectorAll('.peserta-check:checked')].map(c => c.value);
        if (checked.length === 0) {
            document.getElementById('pesertaError').style.display = 'block';
            return;
        }
        document.getElementById('pesertaError').style.display = 'none';

        const id  = document.getElementById('not_id').value;
        const url = id ? `/notulensi/${id}` : `/notulensi`;
        const fakSel = document.getElementById('not_fakultas_id');

        const formData = new FormData();
        formData.append('judul', document.getElementById('not_judul').value);
        formData.append('tanggal', document.getElementById('not_tanggal').value);
        formData.append('tempat', document.getElementById('not_tempat').value);
        
        let agendaContent = '';
        if (tinymce.get('not_agenda')) {
            agendaContent = tinymce.get('not_agenda').getContent();
        } else {
            agendaContent = document.getElementById('not_agenda').value;
        }
        formData.append('agenda', agendaContent);
        formData.append('agenda_rapat', document.getElementById('not_agenda_rapat').value);
        formData.append('tindak_lanjut', document.getElementById('not_tindak_lanjut').value);
        
        checked.forEach(pesertaId => formData.append('peserta[]', pesertaId));
        
        if (fakSel) formData.append('fakultas_id', fakSel.value);
        if (id) formData.append('_method', 'PUT');

        // Append foto-foto baru dari array accumulative
        newFotoFiles.forEach(file => {
            formData.append('dokumentasi[]', file);
        });

        // Kirim ID foto existing yang ingin dihapus
        deletedFotoIds.forEach(dokId => {
            formData.append('deleted_dokumentasi[]', dokId);
        });

        try {
            const res = await fetch(url, {
                method:  'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body:    formData,
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast(data.message, 'success');
                closeModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                const errors = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Terjadi kesalahan.');
                showToast(errors, 'error');
            }
        } catch {
            showToast('Koneksi gagal.', 'error');
        }
    }

    /* ── Delete ────────────────────────────────────── */
    async function deleteNotulensi(id) {
        if (!confirm('Yakin ingin menghapus notulensi ini? Peserta rapat juga akan terhapus.')) return;
        try {
            const res = await fetch(`/notulensi/${id}`, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body:    JSON.stringify({ _method: 'DELETE' }),
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

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#not_agenda',
        menubar: 'file edit view insert format tools table',
        promotion: false,
        toolbar_mode: 'wrap',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
        toolbar: 'undo redo | blocks | ' +
                 'bold italic backcolor | alignleft aligncenter ' +
                 'alignright alignjustify | bullist numlist outdent indent | ' +
                 'removeformat | help',
        content_style: 'body { font-family: "Plus Jakarta Sans", sans-serif; font-size: 13px; }'
    });
</script>
@endpush

