@extends('layouts.dashboard')
@section('title', 'Data Dosen')
@section('subtitle', 'Kelola data dosen dan NIDN')

@section('topbar_actions')
    <button class="btn-primary" onclick="openModal()">
        <i class="ti ti-plus"></i> Tambah Dosen
    </button>
@endsection

@push('styles')
    <style>
        .master-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; margin-bottom: 24px; }
        .master-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .master-table th, .master-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .master-table th { background-color: #f8fafc; font-weight: 600; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; }
        .master-badge { padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; display: inline-block; }
        .badge-aktif { background: #dcfce7; color: #166534; }
        .badge-nonaktif { background: #fee2e2; color: #991b1b; }
        .icon-btn { background: none; border: none; cursor: pointer; color: #64748b; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; }
        .icon-btn:hover { background: #f1f5f9; color: #2563eb; }
        .icon-btn.delete:hover { color: #ef4444; }
        .btn-primary { background-color: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-primary:disabled { background-color: #93c5fd; cursor: not-allowed; }
        .btn-outline { background-color: #fff; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;}
        .btn-outline:hover { background-color: #f1f5f9; }
        .filter-control { border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; font-size: 13px; outline: none; width: 100%; background: #fff; }
        .filter-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .filter-control.error { border-color: #ef4444; }

        .custom-modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.5); align-items: center; justify-content: center; z-index: 50; }
        .custom-modal.active { display: flex; }
        .custom-modal-content { background: #ffffff; width: 480px; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15); font-family: 'Plus Jakarta Sans', sans-serif; max-height: 90vh; overflow-y: auto; }
        .custom-modal-header { padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 600; display: flex; justify-content: space-between; align-items: center; font-size: 16px; position: sticky; top: 0; background: #fff; z-index: 1; }
        .custom-modal-body { padding: 20px; font-size: 13px; }
        .custom-modal-footer { padding: 16px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; border-radius: 0 0 12px 12px; position: sticky; bottom: 0; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: #374151; }
        .form-group label span.req { color: #ef4444; margin-left: 2px; }
        .field-error { color: #ef4444; font-size: 11px; margin-top: 4px; display: none; }
        .field-hint { color: #94a3b8; font-size: 11px; margin-top: 4px; }

        #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
        .custom-toast { min-width: 280px; background: #fff; border-left: 4px solid #10b981; box-shadow: 0 4px 12px -2px rgba(0,0,0,0.12); padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideIn 0.3s ease forwards; font-family: 'Plus Jakarta Sans', sans-serif;}
        .custom-toast.error { border-left-color: #ef4444; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Confirm modal */
        .confirm-modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.5); align-items: center; justify-content: center; z-index: 60; }
        .confirm-modal.active { display: flex; }
        .confirm-box { background: #fff; border-radius: 12px; padding: 24px; width: 360px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15); text-align: center; }
        .confirm-box .confirm-icon { font-size: 40px; color: #ef4444; margin-bottom: 12px; }
        .confirm-box h3 { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
        .confirm-box p { font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .confirm-actions { display: flex; justify-content: center; gap: 10px; }

        /* Inline nominal edit */
        .nominal-wrap { display: flex; align-items: center; gap: 6px; min-width: 180px; }
        .nominal-input { border: 1px solid #e2e8f0; padding: 5px 8px; border-radius: 6px; font-size: 12px; width: 110px; outline: none; }
        .nominal-input:focus { border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.12); }
        .btn-save-nominal { background: #2563eb; color: #fff; border: none; border-radius: 6px; padding: 5px 10px; font-size: 11px; font-weight: 600; cursor: pointer; white-space: nowrap; display: inline-flex; align-items: center; gap: 4px; }
        .btn-save-nominal:hover { background: #1d4ed8; }
        .btn-save-nominal:disabled { background: #93c5fd; cursor: not-allowed; }
        .saved-badge { background: #dcfce7; color: #166534; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 10px; display: none; }
    </style>
@endpush

@section('content')

    <div id="toast-container"></div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('master.dosen.index') }}" class="master-card p-4 mb-6 flex gap-3" style="padding:16px;">
        <input type="text" name="search" class="filter-control flex-1" placeholder="Cari nama / NIDN..." value="{{ request('search') }}">

        <select name="fakultas_id" class="filter-control" style="width:200px;" id="filter_fakultas" onchange="loadFilterProdi()">
            <option value="">Semua Fakultas</option>
            @foreach($fakultasList as $fak)
                <option value="{{ $fak->id }}" {{ request('fakultas_id') == $fak->id ? 'selected' : '' }}>{{ $fak->nama_fakultas }}</option>
            @endforeach
        </select>

        <select name="prodi_id" class="filter-control" style="width:200px;" id="filter_prodi" data-selected="{{ request('prodi_id') }}">
            <option value="">Semua Prodi</option>
        </select>

        <button type="submit" class="btn-outline">Filter</button>
        <a href="{{ route('master.dosen.index') }}" class="btn-outline text-center">Reset</a>
    </form>

    <div class="master-card">
        <div class="table-responsive">
            <table class="master-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dosen</th>
                        <th>NIDN</th>
                        <th>Fakultas</th>
                        <th>Prodi</th>
                        <th>Status</th>
                        <th>Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dosens as $index => $dosen)
                    <tr>
                        <td>{{ $dosens->firstItem() + $index }}</td>
                        <td>{{ $dosen->nama_lengkap }}</td>
                        <td><span style="font-family: monospace; font-size:12px; background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $dosen->nidn }}</span></td>
                        <td>{{ $dosen->fakultas ? $dosen->fakultas->nama_fakultas : '-' }}</td>
                        <td>{{ $dosen->prodi ? $dosen->prodi->nama_prodi : '-' }}</td>
                        <td>
                            @if($dosen->status == 'aktif') <span class="master-badge badge-aktif">Aktif</span>
                            @else <span class="master-badge badge-nonaktif">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="nominal-wrap">
                                <input type="number" class="nominal-input" id="nominal_input_{{ $dosen->id }}"
                                    value="{{ $dosen->nominal_tagihan ?? 0 }}" min="0" step="1" inputmode="numeric"
                                    placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                <button type="button" class="btn-save-nominal" id="nominal_btn_{{ $dosen->id }}"
                                    onclick="saveNominal({{ $dosen->id }})">
                                    <i class="ti ti-device-floppy" style="font-size:12px;"></i> Simpan
                                </button>
                                <span class="saved-badge" id="nominal_saved_{{ $dosen->id }}">Tersimpan</span>
                            </div>
                        </td>
                        <td>
                            <button class="icon-btn" title="Edit" onclick='editDosen(@json($dosen))'><i class="ti ti-pencil"></i></button>
                            <button class="icon-btn delete" title="Hapus" onclick="confirmDelete({{ $dosen->id }}, '{{ addslashes($dosen->nama_lengkap) }}')"><i class="ti ti-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center; padding: 32px; color:#94a3b8;">
                            <i class="ti ti-user-off" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                            Tidak ada data dosen.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200">
            {{ $dosens->withQueryString()->links() }}
        </div>
    </div>

{{-- MODAL TAMBAH / EDIT DOSEN --}}
<div class="custom-modal" id="dosenModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <span id="modalTitle">Tambah Dosen</span>
            <button class="icon-btn" onclick="closeModal()"><i class="ti ti-x"></i></button>
        </div>
        <form id="dosenForm" onsubmit="saveDosen(event)" novalidate>
            <div class="custom-modal-body">
                <input type="hidden" id="dosen_id">

                <div class="form-group">
                    <label>Nama Lengkap <span class="req">*</span></label>
                    <input type="text" id="nama" class="filter-control" placeholder="Masukkan nama lengkap dosen">
                    <div class="field-error" id="err_nama"></div>
                </div>

                <div class="form-group">
                    <label>NIDN <span class="req">*</span></label>
                    <input type="text" id="nidn" class="filter-control" placeholder="10 digit angka" maxlength="10" inputmode="numeric"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    <div class="field-hint">NIDN harus tepat 10 digit angka.</div>
                    <div class="field-error" id="err_nidn"></div>
                </div>

                <div class="form-group">
                    <label>Fakultas <span class="req">*</span></label>
                    <select id="modal_fakultas_id" class="filter-control" onchange="fetchProdi(this.value)">
                        <option value="">-- Pilih Fakultas --</option>
                        @foreach($fakultasList as $fak)
                            <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                        @endforeach
                    </select>
                    <div class="field-error" id="err_fakultas"></div>
                </div>

                <div class="form-group">
                    <label>Program Studi <span class="req">*</span></label>
                    <select id="prodi_id" class="filter-control">
                        <option value="">-- Pilih Prodi --</option>
                    </select>
                    <div class="field-error" id="err_prodi"></div>
                </div>

                <div class="form-group">
                    <label>Status <span class="req">*</span></label>
                    <select id="status" class="filter-control">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                    <div class="field-error" id="err_status"></div>
                </div>

                <div class="form-group">
                    <label>Nominal tagihan bulanan <span class="req">*</span></label>
                    <input type="number" id="nominal_tagihan" class="filter-control" placeholder="Contoh: 150000" min="0"
                        step="1" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" value="0">
                    <div class="field-hint">Isi 0 jika belum ada nominal khusus.</div>
                    <div class="field-error" id="err_nominal_tagihan"></div>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-primary" id="submitBtn">
                    <i class="ti ti-device-floppy"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div class="confirm-modal" id="confirmModal">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="ti ti-alert-triangle"></i></div>
        <h3>Hapus Dosen?</h3>
        <p id="confirmMessage">Apakah Anda yakin ingin menghapus dosen ini? Akun pengguna terkait juga akan dihapus.</p>
        <div class="confirm-actions">
            <button class="btn-outline" onclick="closeConfirm()">Batal</button>
            <button class="btn-primary" style="background:#ef4444;" id="confirmDeleteBtn" onclick="executeDeletion()">
                <i class="ti ti-trash"></i> Ya, Hapus
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let deleteTargetId = null;

        /* ===== TOAST ===== */
        function showToast(msg, type = 'success') {
            const t = document.createElement('div');
            t.className = `custom-toast ${type}`;
            t.innerHTML = `<i class="ti ti-${type === 'success' ? 'check' : 'alert-circle'}"></i> ${msg}`;
            document.getElementById('toast-container').appendChild(t);
            setTimeout(() => t.remove(), 3500);
        }

        /* ===== VALIDASI ===== */
        function setError(id, msg) {
            const el = document.getElementById(id);
            const input = el?.previousElementSibling?.previousElementSibling || el?.previousElementSibling;
            if (el) { el.textContent = msg; el.style.display = 'block'; }
        }
        function clearError(id) {
            const el = document.getElementById(id);
            if (el) { el.textContent = ''; el.style.display = 'none'; }
        }
        function clearAllErrors() {
            ['err_nama','err_nidn','err_fakultas','err_prodi','err_status','err_nominal_tagihan'].forEach(clearError);
            document.querySelectorAll('#dosenModal .filter-control').forEach(el => el.classList.remove('error'));
        }
        function markError(inputId, errId, msg) {
            document.getElementById(inputId)?.classList.add('error');
            setError(errId, msg);
        }

        function validateForm() {
            clearAllErrors();
            let valid = true;

            const nama = document.getElementById('nama').value.trim();
            const nidn = document.getElementById('nidn').value.trim();
            const fakultasId = document.getElementById('modal_fakultas_id').value;
            const prodiId = document.getElementById('prodi_id').value;
            const status = document.getElementById('status').value;
            const nominalTagihan = document.getElementById('nominal_tagihan').value.trim();

            if (!nama) { markError('nama','err_nama','Nama lengkap wajib diisi.'); valid = false; }
            else if (nama.length < 3) { markError('nama','err_nama','Nama minimal 3 karakter.'); valid = false; }

            if (!nidn) { markError('nidn','err_nidn','NIDN wajib diisi.'); valid = false; }
            else if (!/^\d{10}$/.test(nidn)) { markError('nidn','err_nidn','NIDN harus tepat 10 digit angka.'); valid = false; }

            if (!fakultasId) { markError('modal_fakultas_id','err_fakultas','Pilih fakultas terlebih dahulu.'); valid = false; }
            if (!prodiId) { markError('prodi_id','err_prodi','Pilih program studi terlebih dahulu.'); valid = false; }
            if (!status) { markError('status','err_status','Pilih status dosen.'); valid = false; }
            if (nominalTagihan === '') { markError('nominal_tagihan','err_nominal_tagihan','Nominal tagihan bulanan wajib diisi.'); valid = false; }
            else if (!/^\d+$/.test(nominalTagihan) || parseInt(nominalTagihan, 10) < 0) { markError('nominal_tagihan','err_nominal_tagihan','Nominal harus berupa angka minimum 0.'); valid = false; }

            return valid;
        }

        /* ===== PRODI DYNAMIC ===== */
        async function loadFilterProdi() {
            const fakId = document.getElementById('filter_fakultas').value;
            const select = document.getElementById('filter_prodi');
            const selected = select.dataset.selected;
            select.innerHTML = '<option value="">Semua Prodi</option>';
            if (!fakId) return;
            try {
                const res = await fetch(`/master/prodi/by-fakultas/${fakId}`);
                const data = await res.json();
                data.forEach(p => {
                    select.innerHTML += `<option value="${p.id}" ${selected == p.id ? 'selected' : ''}>${p.nama_prodi}</option>`;
                });
            } catch(e) {}
        }

        async function fetchProdi(fakId, selectedProdiId = null) {
            const select = document.getElementById('prodi_id');
            select.innerHTML = '<option value="">Memuat...</option>';
            if (!fakId) { select.innerHTML = '<option value="">-- Pilih Prodi --</option>'; return; }
            try {
                const res = await fetch(`/master/prodi/by-fakultas/${fakId}`);
                const data = await res.json();
                select.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                data.forEach(p => {
                    select.innerHTML += `<option value="${p.id}" ${selectedProdiId == p.id ? 'selected' : ''}>${p.nama_prodi}</option>`;
                });
            } catch(e) {
                select.innerHTML = '<option value="">Gagal memuat prodi</option>';
            }
        }

        /* ===== MODAL TAMBAH/EDIT ===== */
        function openModal() {
            document.getElementById('dosenForm').reset();
            document.getElementById('dosen_id').value = '';
            document.getElementById('modalTitle').textContent = 'Tambah Dosen';
            document.getElementById('prodi_id').innerHTML = '<option value="">-- Pilih Prodi --</option>';
            clearAllErrors();
            document.getElementById('dosenModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('dosenModal').classList.remove('active');
        }

        async function editDosen(dosen) {
            document.getElementById('modalTitle').textContent = 'Edit Dosen';
            document.getElementById('dosen_id').value = dosen.id;
            document.getElementById('nama').value = dosen.nama_lengkap;
            document.getElementById('nidn').value = dosen.nidn;
            document.getElementById('status').value = dosen.status;
            document.getElementById('nominal_tagihan').value = dosen.nominal_tagihan ?? 0;
            clearAllErrors();

            // Load fakultas & prodi
            const fakId = dosen.prodi && dosen.prodi.fakultas_id ? dosen.prodi.fakultas_id : '';
            document.getElementById('modal_fakultas_id').value = fakId;
            await fetchProdi(fakId, dosen.prodi_id);

            document.getElementById('dosenModal').classList.add('active');
        }

        /* ===== SIMPAN DOSEN ===== */
        async function saveDosen(e) {
            e.preventDefault();
            if (!validateForm()) return;

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ti ti-loader ti-spin"></i> Menyimpan...';

            const id = document.getElementById('dosen_id').value;
            const url = id ? `/master/dosen/${id}` : `/master/dosen`;

            const body = {
                nama_lengkap:    document.getElementById('nama').value.trim(),
                nidn:            document.getElementById('nidn').value.trim(),
                prodi_id:        document.getElementById('prodi_id').value,
                status:          document.getElementById('status').value,
                nominal_tagihan: parseInt(document.getElementById('nominal_tagihan').value || '0', 10),
            };
            if (id) body._method = 'PUT';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });
                const data = await res.json();

                if (res.ok && data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else if (res.status === 422 && data.errors) {
                    // Tampilkan error validasi dari server
                    if (data.errors.nama_lengkap) markError('nama', 'err_nama', data.errors.nama_lengkap[0]);
                    if (data.errors.nidn) markError('nidn', 'err_nidn', data.errors.nidn[0]);
                    if (data.errors.prodi_id) markError('prodi_id', 'err_prodi', data.errors.prodi_id[0]);
                    if (data.errors.status) markError('status', 'err_status', data.errors.status[0]);
                    if (data.errors.nominal_tagihan) markError('nominal_tagihan', 'err_nominal_tagihan', data.errors.nominal_tagihan[0]);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ti ti-device-floppy"></i> Simpan';
                } else {
                    showToast(data.message || 'Terjadi kesalahan.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ti ti-device-floppy"></i> Simpan';
                }
            } catch (err) {
                showToast('Koneksi gagal. Periksa koneksi internet Anda.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ti ti-device-floppy"></i> Simpan';
            }
        }

        /* ===== SIMPAN NOMINAL INLINE ===== */
        async function saveNominal(id) {
            const input = document.getElementById('nominal_input_' + id);
            const btn   = document.getElementById('nominal_btn_' + id);
            const badge = document.getElementById('nominal_saved_' + id);

            const nominal = parseInt(input.value || '0', 10);
            if (isNaN(nominal) || nominal < 0) {
                input.style.borderColor = '#ef4444';
                showToast('Nominal tidak valid.', 'error');
                return;
            }
            input.style.borderColor = '';

            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader ti-spin" style="font-size:12px;"></i>';

            try {
                const res = await fetch(`/master/dosen/${id}/nominal`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ _method: 'PATCH', nominal_tagihan: nominal })
                });
                const data = await res.json();

                if (res.ok && data.success) {
                    badge.style.display = 'inline-block';
                    setTimeout(() => badge.style.display = 'none', 2500);
                } else {
                    showToast(data.message || 'Gagal menyimpan nominal.', 'error');
                }
            } catch(e) {
                showToast('Koneksi gagal.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-device-floppy" style="font-size:12px;"></i> Simpan';
            }
        }

        /* ===== HAPUS DOSEN ===== */
        function confirmDelete(id, nama) {
            deleteTargetId = id;
            document.getElementById('confirmMessage').textContent =
                `Apakah Anda yakin ingin menghapus dosen "${nama}"? Akun pengguna terkait juga akan dihapus.`;
            document.getElementById('confirmModal').classList.add('active');
        }

        function closeConfirm() {
            deleteTargetId = null;
            document.getElementById('confirmModal').classList.remove('active');
        }

        async function executeDeletion() {
            if (!deleteTargetId) return;

            const btn = document.getElementById('confirmDeleteBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader ti-spin"></i> Menghapus...';

            try {
                const res = await fetch(`/master/dosen/${deleteTargetId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                const data = await res.json();

                if (res.ok && data.success) {
                    showToast(data.message, 'success');
                    closeConfirm();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Gagal menghapus.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ti ti-trash"></i> Ya, Hapus';
                }
            } catch (err) {
                showToast('Koneksi gagal.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-trash"></i> Ya, Hapus';
            }
        }

        // Tutup modal saat klik backdrop
        document.getElementById('dosenModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) closeConfirm();
        });

        // Initialize filter prodi on page load
        window.addEventListener('DOMContentLoaded', () => {
            loadFilterProdi();
        });
    </script>
@endpush
