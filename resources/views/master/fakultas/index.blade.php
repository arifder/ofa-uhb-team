@extends('layouts.dashboard')
@section('title', 'Fakultas & Prodi')
@section('subtitle', 'Kelola data fakultas dan program studi')

@section('topbar_actions')
    <button class="btn-primary" onclick="openFakultasModal()">
        <i class="ti ti-plus"></i> Tambah Fakultas
    </button>
@endsection

@push('styles')
    <style>
        .master-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; margin-bottom: 24px; }
        .master-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .master-table th, .master-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .master-table th { background-color: #f8fafc; font-weight: 600; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; }
        .master-table tbody tr:last-child td { border-bottom: none; }
        .icon-btn { background: none; border: none; cursor: pointer; color: #64748b; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; }
        .icon-btn:hover { background: #f1f5f9; color: #2563eb; }
        .icon-btn.delete:hover { color: #ef4444; }
        .btn-primary { background-color: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-outline { background-color: #fff; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;}
        .btn-outline:hover { background-color: #f1f5f9; }
        .filter-control { border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; font-size: 13px; outline: none; width: 100%; }
        .filter-control:focus { border-color: #2563eb; }

        /* Stat Cards */
        .stat-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .stat-icon.blue { background: #EFF6FF; color: #2563EB; }
        .stat-icon.teal { background: #F0FDFA; color: #0D9488; }
        .stat-label { font-size: 12px; color: #64748b; margin-bottom: 2px; }
        .stat-value { font-size: 22px; font-weight: 700; color: #1e293b; }

        .custom-modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.5); align-items: center; justify-content: center; z-index: 50; }
        .custom-modal.active { display: flex; }
        .custom-modal-content { background: #ffffff; width: 450px; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); font-family: 'Plus Jakarta Sans', sans-serif;}
        .custom-modal-header { padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 600; display: flex; justify-content: space-between; align-items: center; font-size: 16px; }
        .custom-modal-body { padding: 20px; font-size: 13px; }
        .custom-modal-footer { padding: 16px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; border-radius: 0 0 12px 12px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 6px; color: #475569;}

        .card-header-main { padding: 20px; display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e2e8f0; }
        .fakultas-title { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .fakultas-meta { font-size: 13px; color: #64748b; }
        .section-title { font-size: 14px; font-weight: 600; padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; color: #334155; }

        #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
        .custom-toast { min-width: 250px; background: #fff; border-left: 4px solid #10b981; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideIn 0.3s ease forwards; font-family: 'Plus Jakarta Sans', sans-serif;}
        .custom-toast.error { border-left-color: #ef4444; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    </style>
@endpush

@section('content')

    <div id="toast-container"></div>

    {{-- Stat Cards --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="ti ti-building"></i></div>
            <div>
                <div class="stat-label">Total Fakultas</div>
                <div class="stat-value">{{ $fakultas->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal"><i class="ti ti-school"></i></div>
            <div>
                <div class="stat-label">Total Program Studi</div>
                <div class="stat-value">{{ $totalProdi }}</div>
            </div>
        </div>
    </div>

    {{-- Fakultas Cards --}}
    @foreach($fakultas as $fak)
    <div class="master-card">
        {{-- Header --}}
        <div class="card-header-main">
            <div>
                <div class="fakultas-title">{{ $fak->nama_fakultas }}</div>
                <div class="fakultas-meta">{{ count($fak->prodis) }} Program Studi</div>
            </div>
            <div style="display: flex; gap: 8px;">
                <button class="btn-outline" onclick='editFakultas(@json($fak))'><i class="ti ti-pencil"></i> Edit</button>
                <button class="btn-outline" style="color:#ef4444" onclick="deleteFakultas({{ $fak->id }})"><i class="ti ti-trash"></i> Hapus</button>
            </div>
        </div>

        {{-- Prodi Table --}}
        <div class="section-title">
            <span>Program Studi</span>
            <button class="btn-primary" style="padding: 4px 10px; font-size: 12px;" onclick="openProdiModal({{ $fak->id }})">
                <i class="ti ti-plus"></i> Tambah Prodi
            </button>
        </div>
        @if(count($fak->prodis) > 0)
        <div class="table-responsive">
            <table class="master-table">
                <thead>
                    <tr>
                        <th style="width: 50px">No</th>
                        <th>Nama Prodi</th>
                        <th style="width: 100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fak->prodis as $idx => $prodi)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $prodi->nama_prodi }}</td>
                        <td>
                            <button class="icon-btn" onclick='editProdi(@json($prodi))'><i class="ti ti-pencil"></i></button>
                            <button class="icon-btn delete" onclick="deleteProdi({{ $prodi->id }})"><i class="ti ti-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="padding: 24px; text-align: center; color: #94a3b8; font-size: 13px;">Belum ada program studi.</div>
        @endif
    </div>
    @endforeach

{{-- Modal Fakultas --}}
<div class="custom-modal" id="fakultasModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <span id="fakultasModalTitle">Tambah Fakultas</span>
            <button class="icon-btn" onclick="closeFakultasModal()"><i class="ti ti-x"></i></button>
        </div>
        <form id="fakultasForm" onsubmit="saveFakultas(event)">
            <div class="custom-modal-body">
                <input type="hidden" id="fakultas_id_edit">
                <div class="form-group">
                    <label>Nama Fakultas <span style="color:#ef4444">*</span></label>
                    <input type="text" id="nama_fakultas" class="filter-control" placeholder="Contoh: Sains & Teknologi" required>
                    <div id="err_nama_fakultas" style="color:#ef4444; font-size:11px; margin-top:4px; display:none;"></div>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-outline" onclick="closeFakultasModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Prodi --}}
<div class="custom-modal" id="prodiModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <span id="prodiModalTitle">Tambah Prodi</span>
            <button class="icon-btn" onclick="closeProdiModal()"><i class="ti ti-x"></i></button>
        </div>
        <form id="prodiForm" onsubmit="saveProdi(event)">
            <div class="custom-modal-body">
                <input type="hidden" id="prodi_id_edit">
                <input type="hidden" id="prodi_fakultas_id">
                <div class="form-group">
                    <label>Nama Prodi <span style="color:#ef4444">*</span></label>
                    <input type="text" id="nama_prodi" class="filter-control" placeholder="Contoh: S1 Informatika" required>
                    <div id="err_nama_prodi" style="color:#ef4444; font-size:11px; margin-top:4px; display:none;"></div>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-outline" onclick="closeProdiModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showToast(msg, type = 'success') {
            const t = document.createElement('div');
            t.className = `custom-toast ${type === 'error' ? 'error' : ''}`;
            t.innerHTML = `<i class="ti ti-${type === 'success' ? 'check' : 'alert-circle'}"></i> ${msg}`;
            document.getElementById('toast-container').appendChild(t);
            setTimeout(() => t.remove(), 3000);
        }

        function showError(id, msg) {
            const el = document.getElementById(id);
            if (el) { el.textContent = msg; el.style.display = 'block'; }
        }
        function clearErrors(...ids) {
            ids.forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.textContent = ''; el.style.display = 'none'; }
            });
        }

        // --- FAKULTAS ---
        function openFakultasModal() {
            document.getElementById('fakultasForm').reset();
            document.getElementById('fakultas_id_edit').value = '';
            document.getElementById('fakultasModalTitle').textContent = 'Tambah Fakultas';
            clearErrors('err_nama_fakultas');
            document.getElementById('fakultasModal').classList.add('active');
        }
        function closeFakultasModal() {
            document.getElementById('fakultasModal').classList.remove('active');
        }

        function editFakultas(fak) {
            document.getElementById('fakultasModalTitle').textContent = 'Edit Fakultas';
            document.getElementById('fakultas_id_edit').value = fak.id;
            document.getElementById('nama_fakultas').value = fak.nama_fakultas;
            clearErrors('err_nama_fakultas');
            document.getElementById('fakultasModal').classList.add('active');
        }

        async function saveFakultas(e) {
            e.preventDefault();
            clearErrors('err_nama_fakultas');

            const id = document.getElementById('fakultas_id_edit').value;
            const nama = document.getElementById('nama_fakultas').value.trim();

            if (!nama) { showError('err_nama_fakultas', 'Nama fakultas tidak boleh kosong.'); return; }

            const url = id ? `/master/fakultas/${id}` : `/master/fakultas`;
            const body = { nama_fakultas: nama };
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
                } else {
                    if (data.errors?.nama_fakultas) showError('err_nama_fakultas', data.errors.nama_fakultas[0]);
                    else showToast(data.message || 'Error', 'error');
                }
            } catch (err) { showToast('Koneksi gagal', 'error'); }
        }

        async function deleteFakultas(id) {
            if (!window.confirm('Hapus fakultas ini beserta semua prodi di dalamnya?')) return;
            try {
                const res = await fetch(`/master/fakultas/${id}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else { showToast(data.message || 'Error', 'error'); }
            } catch (err) { showToast('Koneksi gagal', 'error'); }
        }

        // --- PRODI ---
        function openProdiModal(fakultasId) {
            document.getElementById('prodiForm').reset();
            document.getElementById('prodi_id_edit').value = '';
            document.getElementById('prodi_fakultas_id').value = fakultasId;
            document.getElementById('prodiModalTitle').textContent = 'Tambah Prodi';
            clearErrors('err_nama_prodi');
            document.getElementById('prodiModal').classList.add('active');
        }
        function closeProdiModal() {
            document.getElementById('prodiModal').classList.remove('active');
        }

        function editProdi(prodi) {
            document.getElementById('prodiModalTitle').textContent = 'Edit Prodi';
            document.getElementById('prodi_id_edit').value = prodi.id;
            document.getElementById('prodi_fakultas_id').value = prodi.fakultas_id;
            document.getElementById('nama_prodi').value = prodi.nama_prodi;
            clearErrors('err_nama_prodi');
            document.getElementById('prodiModal').classList.add('active');
        }

        async function saveProdi(e) {
            e.preventDefault();
            clearErrors('err_nama_prodi');

            const id = document.getElementById('prodi_id_edit').value;
            const nama = document.getElementById('nama_prodi').value.trim();

            if (!nama) { showError('err_nama_prodi', 'Nama prodi tidak boleh kosong.'); return; }

            const url = id ? `/master/prodi/${id}` : `/master/prodi`;
            const body = {
                nama_prodi: nama,
                fakultas_id: document.getElementById('prodi_fakultas_id').value
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
                } else {
                    if (data.errors?.nama_prodi) showError('err_nama_prodi', data.errors.nama_prodi[0]);
                    else showToast(data.message || 'Error', 'error');
                }
            } catch (err) { showToast('Koneksi gagal', 'error'); }
        }

        async function deleteProdi(id) {
            if (!window.confirm('Hapus prodi ini?')) return;
            try {
                const res = await fetch(`/master/prodi/${id}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else { showToast(data.message || 'Error', 'error'); }
            } catch (err) { showToast('Koneksi gagal', 'error'); }
        }
    </script>
@endpush
