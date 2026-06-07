@extends('layouts.dashboard')
@section('title', 'Data Dosen')

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
    </style>
@endpush

@section('content')
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-size: 18px; font-weight: 600; color: #1e293b;">Data Dosen</h2>
        <button class="btn-primary" onclick="openModal()">
            <i class="ti ti-plus"></i> Tambah Dosen
        </button>
    </div>

    <div id="toast-container"></div>

            <form method="GET" action="{{ route('master.dosen.index') }}" class="master-card p-4 mb-6 flex gap-3">
                <input type="text" name="search" class="filter-control flex-1" placeholder="Cari nama / NIDN..." value="{{ request('search') }}">
                
                <select name="fakultas_id" class="filter-control w-48" id="filter_fakultas" onchange="loadFilterProdi()">
                    <option value="">Semua Fakultas</option>
                    @foreach($fakultasList as $fak)
                        <option value="{{ $fak->id }}" {{ request('fakultas_id') == $fak->id ? 'selected' : '' }}>{{ $fak->nama_fakultas }}</option>
                    @endforeach
                </select>
                
                <select name="prodi_id" class="filter-control w-48" id="filter_prodi" data-selected="{{ request('prodi_id') }}">
                    <option value="">Semua Prodi</option>
                </select>

                <button type="submit" class="btn-outline">Filter</button>
                <a href="{{ route('master.dosen.index') }}" class="btn-outline text-center">Reset</a>
            </form>

            <div class="master-card">
                <table class="master-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIDN</th>
                            <th>Fakultas</th>
                            <th>Prodi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dosens as $index => $dosen)
                        <tr>
                            <td>{{ $dosens->firstItem() + $index }}</td>
                            <td>{{ $dosen->nama_lengkap }}</td>
                            <td>{{ $dosen->nidn }}</td>
                            <td>{{ $dosen->fakultas ? $dosen->fakultas->nama_fakultas : '-' }}</td>
                            <td>{{ $dosen->prodi ? $dosen->prodi->nama_prodi : '-' }}</td>
                            <td>
                                @if($dosen->status == 'aktif') <span class="master-badge badge-aktif">Aktif</span>
                                @else <span class="master-badge badge-nonaktif">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <button class="icon-btn" onclick='editDosen(@json($dosen))'><i class="ti ti-pencil"></i></button>
                                <button class="icon-btn delete" onclick="deleteDosen({{ $dosen->id }})"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-200">
                    {{ $dosens->withQueryString()->links() }}
                </div>
            </div>


<div class="custom-modal" id="dosenModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <span id="modalTitle">Tambah Dosen</span>
            <button class="icon-btn" onclick="closeModal()"><i class="ti ti-x"></i></button>
        </div>
        <form id="dosenForm" onsubmit="saveDosen(event)">
            <div class="custom-modal-body">
                <input type="hidden" id="dosen_id">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" id="nama" class="filter-control" required>
                </div>
                <div class="form-group">
                    <label>NIDN</label>
                    <input type="text" id="nidn" class="filter-control" maxlength="10" pattern="[0-9]{10}" title="NIDN harus 10 digit angka" required>
                </div>
                <div class="form-group">
                    <label>Fakultas</label>
                    <select id="fakultas_id" class="filter-control" required onchange="fetchProdi(this.value)">
                        <option value="">Pilih Fakultas</option>
                        @foreach($fakultasList as $fak)
                            <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Prodi</label>
                    <select id="prodi_id" class="filter-control" required>
                        <option value="">Pilih Prodi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="status" class="filter-control" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal()">Batal</button>
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
            if (!fakId) {
                select.innerHTML = '<option value="">Pilih Prodi</option>';
                return;
            }

            try {
                const res = await fetch(`/master/prodi/by-fakultas/${fakId}`);
                const data = await res.json();
                select.innerHTML = '<option value="">Pilih Prodi</option>';
                data.forEach(p => {
                    select.innerHTML += `<option value="${p.id}" ${selectedProdiId == p.id ? 'selected' : ''}>${p.nama_prodi}</option>`;
                });
            } catch(e) {
                select.innerHTML = '<option value="">Gagal memuat prodi</option>';
            }
        }

        function openModal() {
            document.getElementById('dosenForm').reset();
            document.getElementById('dosen_id').value = '';
            document.getElementById('modalTitle').textContent = 'Tambah Dosen';
            document.getElementById('prodi_id').innerHTML = '<option value="">Pilih Prodi</option>';
            document.getElementById('dosenModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('dosenModal').classList.remove('active');
        }

        function editDosen(dosen) {
            document.getElementById('modalTitle').textContent = 'Edit Dosen';
            document.getElementById('dosen_id').value = dosen.id;
            document.getElementById('nama').value = dosen.nama_lengkap;
            document.getElementById('nidn').value = dosen.nidn;
            
            const fakId = dosen.prodi && dosen.prodi.fakultas_id ? dosen.prodi.fakultas_id : '';
            document.getElementById('fakultas_id').value = fakId;
            
            fetchProdi(fakId, dosen.prodi_id);
            
            document.getElementById('status').value = dosen.status;
            document.getElementById('dosenModal').classList.add('active');
        }

        async function saveDosen(e) {
            e.preventDefault();
            const id = document.getElementById('dosen_id').value;
            const url = id ? `/master/dosen/${id}` : `/master/dosen`;
            
            const body = {
                nama_lengkap: document.getElementById('nama').value,
                nidn: document.getElementById('nidn').value,
                prodi_id: document.getElementById('prodi_id').value,
                status: document.getElementById('status').value,
            };

            if (id) {
                body._method = 'PUT';
            }

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan validasi', 'error');
                }
            } catch (err) {
                showToast('Koneksi gagal', 'error');
            }
        }

        async function deleteDosen(id) {
            if (!window.confirm('Apakah Anda yakin ingin menghapus dosen ini?')) return;
            try {
                const res = await fetch(`/master/dosen/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (err) {
                showToast('Koneksi gagal', 'error');
            }
        }

        // Initialize prodi filter on load
        window.addEventListener('DOMContentLoaded', () => {
            loadFilterProdi();
        });
    </script>
@endpush
