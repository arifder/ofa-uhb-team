<div>
    @php
        $authUser = auth()->user();
        $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $exportQuery = array_filter([
            'search' => $search,
            'fakultas_id' => $fakultasId,
            'status' => $status,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ], fn ($value) => $value !== '' && $value !== null);
    @endphp

    <div class="master-card p-4 mb-4">
        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <input type="text" class="filter-control" placeholder="Cari nama dosen..." wire:model.live.debounce.500ms="search" style="flex:1; min-width:200px;">

            @if(in_array($authUser->role, ['super_admin', 'kepala_unit']))
                <select class="filter-control" style="width:180px;" wire:model.live="fakultasId">
                    <option value="">Semua Fakultas</option>
                    @foreach($fakultasList as $fak)
                        <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                    @endforeach
                </select>
            @endif

            <select class="filter-control" style="width:140px;" wire:model.live="status">
                <option value="">Semua Status</option>
                <option value="belum_lunas">Belum Lunas</option>
                <option value="lunas">Lunas</option>
            </select>

            <select class="filter-control" style="width:120px;" wire:model.live="bulan">
                <option value="">Bulan</option>
                @foreach($bulanList as $i => $nama)
                    <option value="{{ $i + 1 }}">{{ $nama }}</option>
                @endforeach
            </select>

            <select class="filter-control" style="width:110px;" wire:model.live="tahun">
                <option value="">Tahun</option>
                @foreach(range(now()->year, now()->year - 5) as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
            </select>

            <button type="button" class="btn-outline" wire:click="resetFilters">Reset</button>
            <a href="{{ route('kas.tagihan.exportPdf', $exportQuery) }}" class="btn-outline" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                <i class="ti ti-file-export"></i> Export PDF
            </a>
        </div>
    </div>

    <div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:24px;">
        <div class="stat-card stat-saldo" style="flex:1; min-width:200px;">
            <span class="stat-label">Total Tagihan</span>
            <span class="stat-number" style="color:#475569;">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card stat-masuk" style="flex:1; min-width:200px;">
            <span class="stat-label">Total Terbayar</span>
            <span class="stat-number" style="color:#059669;">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card stat-keluar" style="flex:1; min-width:200px;">
            <span class="stat-label">Sisa Tagihan</span>
            <span class="stat-number" style="color:#dc2626;">Rp {{ number_format($totalSisa, 0, ',', '.') }}</span>
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
                        <th>Dosen</th>
                        <th>Fakultas</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihanList as $index => $tagihan)
                        <tr wire:key="tagihan-{{ $tagihan->id }}">
                            <td>{{ $tagihanList->firstItem() + $index }}</td>
                            <td style="font-weight:500;">{{ $tagihan->dosen?->nama_lengkap ?? '-' }}</td>
                            <td>
                                @if($tagihan->fakultas)
                                    @php
                                        $fakNama = $tagihan->fakultas->nama_fakultas ?? '-';
                                        $fakClass = str_contains(strtolower($fakNama), 'sains') ? 'fak-fst'
                                            : (str_contains(strtolower($fakNama), 'sosial') ? 'fak-fis' : 'fak-other');
                                    @endphp
                                    <span class="fak-badge {{ $fakClass }}">{{ $fakNama }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $bulanList[($tagihan->bulan ?? 1) - 1] }} {{ $tagihan->tahun }}</td>
                            <td style="font-weight:600;">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                            <td>
                                <span class="status-badge {{ $tagihan->status === 'lunas' ? 'status-lunas' : 'status-belum' }}">
                                    {{ $tagihan->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </td>
                            <td>
                                <button class="icon-btn" onclick="viewDetail({{ $tagihan->id }})" title="Lihat Detail">
                                    <i class="ti ti-eye"></i>
                                </button>
                                @if($authUser->role !== 'kepala_unit')
                                    @if($tagihan->status !== 'lunas')
                                        <button class="icon-btn" onclick="openBayarModal({{ $tagihan->id }})" title="Bayar" style="color:#059669;">
                                            <i class="ti ti-cash"></i>
                                        </button>
                                    @endif

                                    @if($authUser->role !== 'dosen')
                                        <button class="icon-btn delete" onclick="deleteTagihan({{ $tagihan->id }})" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                <i class="ti ti-receipt" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                                Belum ada data tagihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pag-wrap">
            <div>
                Menampilkan <b>{{ $tagihanList->firstItem() ?? 0 }}</b>-<b>{{ $tagihanList->lastItem() ?? 0 }}</b>
                dari <b>{{ $tagihanList->total() }}</b> data
            </div>
            <div style="display:flex; gap:6px;">
                {{ $tagihanList->links() }}
            </div>
        </div>
    </div>
</div>
