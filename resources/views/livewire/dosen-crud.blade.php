<div>
    <div class="master-card p-4 mb-6 flex gap-3" style="padding:16px;">
        <input type="text" class="filter-control flex-1" placeholder="Cari nama / NIDN..." wire:model.live.debounce.500ms="search">

        <select class="filter-control" style="width:200px;" wire:model.live="fakultasId">
            <option value="">Semua Fakultas</option>
            @foreach($fakultasList as $fak)
                <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
            @endforeach
        </select>

        <select class="filter-control" style="width:200px;" wire:model.live="prodiId">
            <option value="">Semua Prodi</option>
            @foreach($prodiList as $prodi)
                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
            @endforeach
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
                        <tr wire:key="dosen-{{ $dosen->id }}">
                            <td>{{ $dosens->firstItem() + $index }}</td>
                            <td>{{ $dosen->nama_lengkap }}</td>
                            <td><span style="font-family: monospace; font-size:12px; background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $dosen->nidn }}</span></td>
                            <td>{{ $dosen->prodi?->fakultas?->nama_fakultas ?? '-' }}</td>
                            <td>{{ $dosen->prodi?->nama_prodi ?? '-' }}</td>
                            <td>
                                @if($dosen->status == 'aktif')
                                    <span class="master-badge badge-aktif">Aktif</span>
                                @else
                                    <span class="master-badge badge-nonaktif">Nonaktif</span>
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
                            <td colspan="8" style="text-align:center; padding: 32px; color:#94a3b8;">
                                <i class="ti ti-user-off" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                                Tidak ada data dosen.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $dosens->links() }}
        </div>
    </div>
</div>
