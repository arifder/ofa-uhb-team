@extends('layouts.dashboard')
@section('title', 'Fakultas & Prodi')

@push('styles')
    <style>
        .master-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; margin-bottom: 24px; }
        .master-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .master-table th, .master-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .master-table th { background-color: #f8fafc; font-weight: 600; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; }
        .icon-btn { background: none; border: none; cursor: pointer; color: #64748b; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; }
        .icon-btn:hover { background: #f1f5f9; color: #2563eb; }
        .icon-btn.delete:hover { color: #ef4444; }
        .btn-primary { background-color: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-outline { background-color: #fff; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;}
        .btn-outline:hover { background-color: #f1f5f9; }
        .filter-control { border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; font-size: 13px; outline: none; width: 100%; }
        .filter-control:focus { border-color: #2563eb; }
        
        .custom-modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.5); align-items: center; justify-content: center; z-index: 50; }
        .custom-modal.active { display: flex; }
        .custom-modal-content { background: #ffffff; width: 450px; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); font-family: 'Plus Jakarta Sans', sans-serif;}
        .custom-modal-header { padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 600; display: flex; justify-content: space-between; align-items: center; font-size: 16px; }
        .custom-modal-body { padding: 20px; font-size: 13px; }
        .custom-modal-footer { padding: 16px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; border-radius: 0 0 12px 12px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 6px; color: #475569;}
        
        #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
        .custom-toast { min-width: 250px; background: #fff; border-left: 4px solid #10b981; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 10px; animation: slideIn 0.3s ease forwards; font-family: 'Plus Jakarta Sans', sans-serif;}
        .custom-toast.error { border-left-color: #ef4444; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        .card-header-main { padding: 20px; display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e2e8f0; }
        .fakultas-title { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .fakultas-meta { font-size: 13px; color: #64748b; }
        .section-title { font-size: 14px; font-weight: 600; padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; color: #334155; }
        .master-badge { padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; display: inline-block; }
        .badge-aktif { background: #dcfce7; color: #166534; }
        .badge-nonaktif { background: #fee2e2; color: #991b1b; }
    </style>
@endpush

@section('content')
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-size: 18px; font-weight: 600; color: #1e293b;">Fakultas & Prodi</h2>
        <button class="btn-primary" onclick="openFakultasModal()">
            <i class="ti ti-plus"></i> Tambah Fakultas
        </button>
    </div>
    
    <div id="toast-container"></div>

            @foreach($fakultas as $fak)
            <div class="master-card">
                <!-- BAGIAN ATAS KARTU -->
                <div class="card-header-main">
                    <div>
                        <div class="fakultas-title">{{ $fak->nama_fakultas }}</div>
                        <div class="fakultas-meta">{{ count($fak->prodis) }} prodi &middot; {{ $fak->dosens_count }} dosen</div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn-outline" onclick='editFakultas(@json($fak))'><i class="ti ti-pencil"></i> Edit</button>
                        <button class="btn-outline" style="color:#ef4444" onclick="deleteFakultas({{ $fak->id }})"><i class="ti ti-trash"></i> Hapus</button>
                    </div>
                </div>

                <!-- BAGIAN TENGAH: TABEL PRODI -->
                <div class="section-title">
                    <span>Program Studi</span>
                    <button class="btn-primary" style="padding: 4px 10px; font-size: 12px;" onclick="openProdiModal({{ $fak->id }})">
                        <i class="ti ti-plus"></i> Tambah Prodi
                    </button>
                </div>
                @if(count($fak->prodis) > 0)
                <table class="master-table">
                    <thead>
                        <tr>
                            <th style="width: 50px">No</th>
                            <th>Nama Prodi</th>
                            <th>Jumlah Dosen</th>
                            <th style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fak->prodis as $idx => $prodi)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $prodi->nama_prodi }}</td>
                            <td>{{ count($prodi->dosens) }} Dosen</td>
                            <td>
                                <button class="icon-btn" onclick='editProdi(@json($prodi))'><i class="ti ti-pencil"></i></button>
                                <button class="icon-btn delete" onclick="deleteProdi({{ $prodi->id }})"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-4 text-center text-sm text-gray-500">Belum ada prodi.</div>
                @endif

                <!-- BAGIAN BAWAH: TABEL DOSEN -->
                <div class="section-title border-t mt-4">
                    <span>Dosen di Fakultas ini</span>
                </div>
                @php
                    // Ambil koleksi dosen untuk fakultas ini
                    // Karena struktur sudah dimuat dengan dosens di model
                    $dosensFakultas = $fak->dosens;
                @endphp
                @if(count($dosensFakultas) > 0)
                <table class="master-table">
                    <thead>
                        <tr>
                            <th style="width: 50px">No</th>
                            <th>Nama Dosen</th>
                            <th>NIDN</th>
                            <th>Prodi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dosensFakultas as $idx => $dsn)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $dsn->nama_lengkap }}</td>
                            <td>{{ $dsn->nidn }}</td>
                            <td>{{ $dsn->prodi ? $dsn->prodi->nama_prodi : '-' }}</td>
                            <td>
                                @if($dsn->status == 'aktif') <span class="master-badge badge-aktif">Aktif</span>
                                @else <span class="master-badge badge-nonaktif">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-4 text-center text-sm text-gray-500">Belum ada dosen terdaftar di fakultas ini.</div>
                @endif
            </div>
            @endforeach
<!-- Modals -->
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
                    <label>Nama Fakultas</label>
                    <input type="text" id="nama_fakultas" class="filter-control" required>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-outline" onclick="closeFakultasModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

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
                    <label>Nama Prodi</label>
                    <input type="text" id="nama_prodi" class="filter-control" required>
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
            t.className = `custom-toast ${type}`;
            t.innerHTML = `<i class="ti ti-${type === 'success' ? 'check' : 'alert-circle'}"></i> ${msg}`;
            document.getElementById('toast-container').appendChild(t);
            setTimeout(() => t.remove(), 3000);
        }

        // --- FAKULTAS ---
        function openFakultasModal() {
            document.getElementById('fakultasForm').reset();
            document.getElementById('fakultas_id_edit').value = '';
            document.getElementById('fakultasModalTitle').textContent = 'Tambah Fakultas';
            document.getElementById('fakultasModal').classList.add('active');
        }
        function closeFakultasModal() { document.getElementById('fakultasModal').classList.remove('active'); }
        
        function editFakultas(fak) {
            document.getElementById('fakultasModalTitle').textContent = 'Edit Fakultas';
            document.getElementById('fakultas_id_edit').value = fak.id;
            document.getElementById('nama_fakultas').value = fak.nama_fakultas;
            document.getElementById('fakultasModal').classList.add('active');
        }

        async function saveFakultas(e) {
            e.preventDefault();
            const id = document.getElementById('fakultas_id_edit').value;
            const url = id ? `/master/fakultas/${id}` : `/master/fakultas`;
            const body = { nama_fakultas: document.getElementById('nama_fakultas').value };
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
                } else { showToast(data.message || 'Error', 'error'); }
            } catch (err) { showToast('Koneksi gagal', 'error'); }
        }

        async function deleteFakultas(id) {
            if (!window.confirm('Hapus fakultas ini beserta semua isinya?')) return;
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
            document.getElementById('prodiModal').classList.add('active');
        }
        function closeProdiModal() { document.getElementById('prodiModal').classList.remove('active'); }
        
        function editProdi(prodi) {
            document.getElementById('prodiModalTitle').textContent = 'Edit Prodi';
            document.getElementById('prodi_id_edit').value = prodi.id;
            document.getElementById('prodi_fakultas_id').value = prodi.fakultas_id;
            document.getElementById('nama_prodi').value = prodi.nama_prodi;
            document.getElementById('prodiModal').classList.add('active');
        }

        async function saveProdi(e) {
            e.preventDefault();
            const id = document.getElementById('prodi_id_edit').value;
            const url = id ? `/master/prodi/${id}` : `/master/prodi`;
            const body = { 
                nama_prodi: document.getElementById('nama_prodi').value,
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
                } else { showToast(data.message || 'Error', 'error'); }
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
