@extends('layouts.dashboard')
@section('title', 'Manajemen User')

@push('styles')
    <style>
        .master-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; margin-bottom: 24px; }
        .master-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .master-table th, .master-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .master-table th { background-color: #f8fafc; font-weight: 600; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; }
        .master-badge { padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; display: inline-block; }
        .badge-sa { background: #dbeafe; color: #1d4ed8; }
        .badge-af { background: #fef3c7; color: #92400e; }
        .badge-ku { background: #d1fae5; color: #065f46; }
        .badge-ds { background: #ede9fe; color: #5b21b6; }
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
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-size: 18px; font-weight: 600; color: #1e293b;">
            Daftar User <span style="font-size: 13px; font-weight: 400; color: #64748b; margin-left: 8px;">Total: {{ $users->total() }}</span>
        </h2>
        <button class="btn-primary" onclick="window.dispatchEvent(new CustomEvent('open-user-modal'))">
            <i class="ti ti-plus"></i> Tambah User
        </button>
    </div>

    <div id="toast-container"></div>

            <!-- Filter Bar -->
            <form method="GET" action="{{ route('master.users.index') }}" class="master-card p-4 mb-6 flex gap-3">
                <input type="text" name="search" class="filter-control flex-1" placeholder="Cari nama / username..." value="{{ request('search') }}">
                <select name="role" class="filter-control w-48" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin_kas_fst" {{ request('role') == 'admin_kas_fst' ? 'selected' : '' }}>Admin Kas FST</option>
                    <option value="admin_notulensi_fst" {{ request('role') == 'admin_notulensi_fst' ? 'selected' : '' }}>Admin Notulensi FST</option>
                    <option value="admin_kas_fis" {{ request('role') == 'admin_kas_fis' ? 'selected' : '' }}>Admin Kas FIS</option>
                    <option value="admin_notulensi_fis" {{ request('role') == 'admin_notulensi_fis' ? 'selected' : '' }}>Admin Notulensi FIS</option>
                    <option value="kepala_unit" {{ request('role') == 'kepala_unit' ? 'selected' : '' }}>Kepala Unit</option>
                    <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                </select>
                <button type="submit" class="btn-outline">Filter</button>
                <a href="{{ route('master.users.index') }}" class="btn-outline text-center">Reset</a>
            </form>

            <div class="master-card">
                <table class="master-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Fakultas</th>
                            <th>Prodi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span style="
                                  background:{{ $user->role_badge_color['bg'] }};
                                  color:{{ $user->role_badge_color['text'] }};
                                  padding:2px 10px;
                                  border-radius:99px;
                                  font-size:11px;
                                  font-weight:600">
                                  {{ $user->role_label }}
                                </span>
                            </td>
                            <td>{{ $user->fakultas ? $user->fakultas->nama_fakultas : '-' }}</td>
                            <td>{{ $user->prodi ? $user->prodi->nama_prodi : '-' }}</td>
                            <td>
                                @if($user->status == 'aktif') <span class="master-badge badge-aktif">Aktif</span>
                                @else <span class="master-badge badge-nonaktif">Arsip</span>
                                @endif
                            </td>
                            <td>
                                <button class="icon-btn" onclick='window.dispatchEvent(new CustomEvent("open-user-modal", { detail: @js($user) }))' title="Edit User"><i class="ti ti-pencil"></i></button>
                                <button class="icon-btn" style="color:#d97706" onclick="toggleStatus({{ $user->id }})" title="Arsipkan User"><i class="ti ti-archive"></i></button>
                                <button class="icon-btn delete" onclick="deleteUser({{ $user->id }})" title="Hapus Permanen"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-200 pagination-container">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>

    <style>
        /* Fix mobile view showing duplicate nav */
        .pagination-container nav > div:first-child { display: none; }
        
        /* Flexbox layout for desktop nav */
        .pagination-container nav > div:last-child {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        /* Styling for the 'Showing...' text */
        .pagination-container nav p {
            margin: 0;
            font-size: 13px;
            color: #64748b;
        }
        
        /* Remove weird borders from spans inside the paragraph */
        .pagination-container nav p span {
            font-weight: 600;
            color: #1e293b;
        }

        /* Styling for the pagination links container */
        .pagination-container nav span.shadow-sm,
        .pagination-container nav .relative.inline-flex {
            display: inline-flex;
            gap: 4px;
            box-shadow: none;
        }

        /* SVG Arrow Icons */
        .pagination-container svg { width: 16px; height: 16px; display: inline; }

        /* Styling for pagination buttons (both span and a) */
        .pagination-container nav .shadow-sm > span,
        .pagination-container nav .shadow-sm > a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            color: #475569;
            background: #ffffff;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination-container nav .shadow-sm > a:hover {
            background: #f1f5f9;
            color: #2563eb;
        }

        /* Active Page Styling */
        .pagination-container nav .shadow-sm > span[aria-current="page"] > span {
            background: #2563eb !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }
        
        /* Fix for active wrapper span padding */
        .pagination-container nav .shadow-sm > span[aria-current="page"] {
            padding: 0;
            border: none;
        }
    </style>

    <!-- Modal Alpine.js -->
    <div class="custom-modal" id="userModal" x-data="{ 
        isOpen: false,
        userId: '',
        name: '',
        username: '',
        email: '',
        password: '',
        role: 'dosen',
        status: 'aktif',
        fakultasId: '',
        prodiId: '',
        prodis: [],

        get showFakultas() {
            return this.role === 'admin_kas_fst' || 
                   this.role === 'admin_notulensi_fst' || 
                   this.role === 'admin_kas_fis' || 
                   this.role === 'admin_notulensi_fis' || 
                   this.role === 'dosen';
        },
        get showProdi() {
            return this.role === 'dosen';
        },
        async fetchProdi() {
            if (!this.fakultasId) {
                this.prodis = [];
                this.prodiId = '';
                return;
            }
            try {
                const res = await fetch(`/master/prodi/by-fakultas/${this.fakultasId}`);
                this.prodis = await res.json();
                if (this.prodiId && !this.prodis.some(p => p.id == this.prodiId)) {
                    this.prodiId = '';
                }
            } catch(e) {
                console.error(e);
            }
        },
        get autoFakultas() {
            if (this.role.includes('fst')) return '{{ $fstId }}';
            if (this.role.includes('fis')) return '{{ $fisId }}';
            return '';
        },
        init() {
            this.$watch('role', (newRole) => {
                if (newRole.includes('fst') || newRole.includes('fis')) {
                    this.fakultasId = this.autoFakultas;
                    this.fetchProdi();
                } else if (newRole !== 'dosen') {
                    this.fakultasId = '';
                    this.prodiId = '';
                    this.prodis = [];
                }
            });

            this.$watch('fakultasId', () => {
                this.fetchProdi();
            });

            window.addEventListener('open-user-modal', (e) => {
                const user = e.detail;
                if (user) {
                    this.userId = user.id;
                    this.name = user.name;
                    this.username = user.username;
                    this.email = user.email;
                    this.password = '';
                    this.role = user.role;
                    this.status = user.status;
                    this.fakultasId = user.fakultas_id || '';
                    this.prodiId = user.prodi_id || '';
                    this.fetchProdi().then(() => {
                        this.prodiId = user.prodi_id || '';
                    });
                    document.getElementById('modalTitle').textContent = 'Edit User';
                } else {
                    this.userId = '';
                    this.name = '';
                    this.username = '';
                    this.email = '';
                    this.password = '';
                    this.role = 'dosen';
                    this.status = 'aktif';
                    this.fakultasId = '';
                    this.prodiId = '';
                    this.prodis = [];
                    document.getElementById('modalTitle').textContent = 'Tambah User';
                }
                this.isOpen = true;
            });
        },
        closeModal() {
            this.isOpen = false;
        },
        async saveUser(e) {
            e.preventDefault();
            const url = this.userId ? `/master/users/${this.userId}` : `/master/users`;
            const body = {
                name: this.name,
                username: this.username,
                email: this.email,
                password: this.password,
                role: this.role,
                status: this.status,
            };

            if (this.userId) {
                body._method = 'PUT';
            }

            if (this.showFakultas) {
                body.fakultas_id = this.fakultasId;
            }
            if (this.showProdi) {
                body.prodi_id = this.prodiId;
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
                    this.closeModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan validasi', 'error');
                }
            } catch (err) {
                showToast('Koneksi gagal', 'error');
            }
        }
    }" :class="{ active: isOpen }">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <span id="modalTitle">Tambah User</span>
                <button type="button" class="icon-btn" @click="closeModal()"><i class="ti ti-x"></i></button>
            </div>
            <form id="userForm" @submit="saveUser($event)">
                <div class="custom-modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="filter-control" required x-model="name">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="filter-control" required x-model="username">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="filter-control" :required="role !== 'dosen'" placeholder="Kosongkan untuk dosen (otomatis: namadosen@ofa.com)" x-model="email">
                        <div x-show="role === 'dosen'" class="text-xs text-blue-500 mt-1" style="font-size: 11px; color: #3b82f6; margin-top: 4px;">Jika role Dosen dan email dikosongkan, email otomatis: namadosen@ofa.com</div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="filter-control" :required="role !== 'dosen' && !userId" placeholder="Kosongkan jika tidak diubah" x-model="password">
                        <div x-show="role === 'dosen'" class="text-xs text-blue-500 mt-1" style="font-size: 11px; color: #3b82f6; margin-top: 4px;">Jika role Dosen dan password dikosongkan, password otomatis: namadosen123</div>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="filter-control" required x-model="role">
                            <option value="super_admin">Super Admin</option>
                            <option value="admin_kas_fst">Admin Kas FST</option>
                            <option value="admin_notulensi_fst">Admin Notulensi FST</option>
                            <option value="admin_kas_fis">Admin Kas FIS</option>
                            <option value="admin_notulensi_fis">Admin Notulensi FIS</option>
                            <option value="kepala_unit">Kepala Unit</option>
                            <option value="dosen">Dosen</option>
                        </select>
                    </div>
                    <div class="form-group" x-show="showFakultas" x-cloak>
                        <label>Fakultas</label>
                        <select class="filter-control" x-model="fakultasId">
                            <option value="">Pilih Fakultas</option>
                            @foreach($fakultasList as $fak)
                                <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" x-show="showProdi" x-cloak>
                        <label>Program Studi</label>
                        <select class="filter-control" x-model="prodiId">
                            <option value="">Pilih Program Studi</option>
                            <template x-for="p in prodis" :key="p.id">
                                <option :value="p.id" x-text="p.nama_prodi" :selected="p.id == prodiId"></option>
                            </template>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="filter-control" required x-model="status">
                            <option value="aktif">Aktif</option>
                            <option value="arsip">Arsip (Nonaktif)</option>
                        </select>
                    </div>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="btn-outline" @click="closeModal()">Batal</button>
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

        async function deleteUser(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus user ini secara permanen?')) return;
            try {
                const res = await fetch(`/master/users/${id}`, {
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

        async function toggleStatus(id) {
            if (!confirm('Apakah Anda yakin ingin mengarsipkan user ini? User yang diarsipkan tidak bisa login.')) return;
            try {
                const res = await fetch(`/master/users/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
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
    </script>
@endpush
