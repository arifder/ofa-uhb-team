<div>
    <div class="master-card p-4 mb-6 flex gap-3">
        <input type="text" class="filter-control flex-1" placeholder="Cari nama / username..." wire:model.live.debounce.500ms="search">

        <select class="filter-control w-48" wire:model.live="role">
            <option value="">Semua Role</option>
            <option value="super_admin">Super Admin</option>
            <option value="admin_fst">Admin FST</option>
            <option value="admin_fis">Admin FIS</option>
            <option value="kepala_unit">Kepala Unit</option>
            <option value="dosen">Dosen</option>
        </select>

        <button type="button" class="btn-outline" wire:click="resetFilters">Reset</button>
    </div>

    <div class="master-card" style="position:relative;">
        <div wire:loading.delay class="absolute inset-0 z-10 flex items-center justify-center bg-white/70" style="backdrop-filter: blur(2px);">
            <i class="ti ti-loader ti-spin" style="font-size:24px; color:#2563eb;"></i>
        </div>

        <div class="table-responsive">
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
                    @forelse($users as $index => $user)
                        <tr wire:key="archived-user-{{ $user->id }}">
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span style="background:{{ $user->role_badge_color['bg'] }}; color:{{ $user->role_badge_color['text'] }}; padding:2px 10px; border-radius:99px; font-size:11px; font-weight:600">
                                    {{ $user->role_label }}
                                </span>
                            </td>
                            <td>{{ $user->fakultas?->nama_fakultas ?? '-' }}</td>
                            <td>{{ $user->prodi?->nama_prodi ?? '-' }}</td>

                            <td><span class="master-badge badge-nonaktif">Arsip</span></td>
                            <td>
                                <button class="btn-outline" style="padding:4px 8px; font-size:11px; display:inline-flex; align-items:center; gap:4px; color:#059669; border-color:#059669;" onclick="restoreUser({{ $user->id }})" title="Pulihkan User">
                                    <i class="ti ti-rotate-clockwise"></i> Restore
                                </button>
                                <button class="icon-btn delete" onclick="deleteUser({{ $user->id }})" title="Hapus Permanen" style="margin-left: 4px;"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding: 24px; text-align: center; color: #64748b;">Belum ada user yang diarsipkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 pagination-container">
            {{ $users->links() }}
        </div>
    </div>
</div>
