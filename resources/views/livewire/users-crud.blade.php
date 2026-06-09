<div>
    <div class="master-card p-4 mb-6 flex gap-3" style="position:relative;">
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
                        <th>Jabatan</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr wire:key="user-{{ $user->id }}">
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
                            <td>
                                @if($user->jabatan_struktural)
                                    <span style="background:#e0e7ff; color:#4338ca; padding:2px 8px; border-radius:12px; font-size:11px; font-weight:600;">{{ $user->jabatan_struktural }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($user->role === 'dosen' && $user->dosen)
                                    <div class="nominal-wrap">
                                        <input type="number" class="nominal-input" id="user_nominal_input_{{ $user->dosen->id }}"
                                            value="{{ $user->dosen->nominal_tagihan ?? 0 }}" min="0" step="1" inputmode="numeric"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                        <button type="button" class="btn-save-nominal" id="user_nominal_btn_{{ $user->dosen->id }}"
                                            onclick="saveUserNominal({{ $user->dosen->id }})">
                                            <i class="ti ti-device-floppy" style="font-size:12px;"></i> Simpan
                                        </button>
                                        <span class="saved-badge" id="user_nominal_saved_{{ $user->dosen->id }}">Tersimpan</span>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($user->status == 'aktif')
                                    <span class="master-badge badge-aktif">Aktif</span>
                                @else
                                    <span class="master-badge badge-nonaktif">Arsip</span>
                                @endif
                            </td>
                            <td>
                                <button class="icon-btn" onclick='window.dispatchEvent(new CustomEvent("open-user-modal", { detail: @json($user) }))' title="Edit User"><i class="ti ti-pencil"></i></button>
                                <button class="icon-btn" style="color:#d97706" onclick="toggleStatus({{ $user->id }})" title="Arsipkan User"><i class="ti ti-archive"></i></button>
                                <button class="icon-btn delete" onclick="deleteUser({{ $user->id }})" title="Hapus Permanen"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center" style="padding: 24px; text-align: center; color: #64748b;">Tidak ada data user.</td>
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
