<div>
    @php $authUser = auth()->user(); @endphp

    <div class="master-card p-4 mb-4" style="padding:16px;">
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <input type="text" class="filter-control" placeholder="Cari judul notulensi..." wire:model.live.debounce.500ms="search" style="flex:1; min-width:200px;">

            @if(in_array($authUser->role, ['super_admin', 'kepala_unit']))
                <select class="filter-control" style="width:200px;" wire:model.live="fakultasId">
                    <option value="">Semua Fakultas</option>
                    @foreach($fakultasList as $fak)
                        <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                    @endforeach
                </select>
            @endif

            <button type="button" class="btn-outline" wire:click="resetFilters">Reset</button>
        </div>
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
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Tempat</th>
                        <th>Fakultas</th>
                        <th>Peserta</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notulensiList as $index => $not)
                        <tr wire:key="notulensi-{{ $not->id }}">
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
                                @if(!in_array($authUser->role, ['kepala_unit', 'dosen']))
                                    <button class="icon-btn" onclick="editNotulensi({{ $not->id }})" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button class="icon-btn delete" onclick="deleteNotulensi({{ $not->id }})" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @endif
                            </td>
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

        <div class="pag-wrap">
            <div>
                Menampilkan <b>{{ $notulensiList->firstItem() ?? 0 }}</b>-<b>{{ $notulensiList->lastItem() ?? 0 }}</b>
                dari <b>{{ $notulensiList->total() }}</b> notulensi
            </div>
            <div style="display:flex; gap:6px;">
                {{ $notulensiList->links() }}
            </div>
        </div>
    </div>
</div>
