<div>
    @php
        $authUser = auth()->user();
        $bulanList = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
    @endphp

    <div class="master-card mb-4" style="padding:16px;">
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:12px;">
            <input type="text" class="filter-control" placeholder="Cari keterangan..." wire:model.live.debounce.500ms="search" style="flex:1; min-width:260px; height:38px;">

            @if(in_array($authUser->role, ['super_admin', 'kepala_unit']))
                <select class="filter-control" style="width:200px; height:38px;" wire:model.live="fakultasId">
                    <option value="">Semua Fakultas</option>
                    @foreach($fakultasList as $fak)
                        <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                    @endforeach
                </select>
            @endif

            <select class="filter-control" style="width:150px; height:38px;" wire:model.live="filterTipe">
                <option value="hari">Hari</option>
                <option value="bulan">Bulan</option>
                <option value="tahun">Tahun</option>
            </select>

            @if($filterTipe === 'hari')
                <input type="date" class="filter-control" style="width:160px; height:38px;" wire:model.live="tanggalAwal">
                <input type="date" class="filter-control" style="width:160px; height:38px;" wire:model.live="tanggalAkhir">
            @elseif($filterTipe === 'bulan')
                <select class="filter-control" style="width:150px; height:38px;" wire:model.live="bulan">
                    <option value="">Semua Bulan</option>
                    @foreach($bulanList as $num => $name)
                        <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
                <select class="filter-control" style="width:120px; height:38px;" wire:model.live="tahun">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y') + 2; $y >= 2020; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            @else
                <select class="filter-control" style="width:120px; height:38px;" wire:model.live="tahun">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y') + 2; $y >= 2020; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            @endif

            <button type="button" class="btn-outline" wire:click="resetFilters" style="height:38px;">Reset</button>
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
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Fakultas</th>
                        @if($jenis === 'masuk')
                            <th>Dosen</th>
                            <th>Pembagian (Tabungan / Sosial)</th>
                        @elseif($jenis === 'keluar')
                            <th>Kategori</th>
                        @endif
                        <th>Jumlah</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasList as $index => $kas)
                        <tr wire:key="kas-{{ $kas->id }}">
                            <td>{{ $kasList->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($kas->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>
                                <a href="#" onclick="viewDetail({{ $kas->id }})" style="color:#2563eb; font-weight:500; text-decoration:none;">
                                    {{ $kas->keterangan }}
                                </a>
                            </td>
                            <td>
                                @if($kas->fakultas)
                                    @php
                                        $fakNama = $kas->fakultas->nama_fakultas ?? '-';
                                        $fakClass = str_contains(strtolower($fakNama), 'sains') ? 'fak-fst'
                                            : (str_contains(strtolower($fakNama), 'sosial') ? 'fak-fis' : 'fak-other');
                                    @endphp
                                    <span class="fak-badge {{ $fakClass }}">{{ $fakNama }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            @if($jenis === 'masuk')
                                <td>{{ $kas->dosen->nama_lengkap ?? '-' }}</td>
                                <td>
                                    @if($kas->dosen_id && $kas->jumlah > 0)
                                        <div style="font-size:13px;">
                                            <span style="color:#059669; font-family:monospace;">Rp {{ number_format($kas->tabungan, 0, ',', '.') }}</span>
                                            <span style="color:#9ca3af; margin:0 4px;">/</span>
                                            <span style="color:#2563eb; font-family:monospace;">Rp {{ number_format($kas->uang_sosial, 0, ',', '.') }}</span>
                                        </div>
                                        <div style="font-size:11px; color:#9ca3af;">Tabungan / Sosial</div>
                                    @else
                                        -
                                    @endif
                                </td>
                            @elseif($jenis === 'keluar')
                                <td>
                                    @if($kas->kategori)
                                        <span style="background:#f1f5f9; padding:4px 8px; border-radius:6px; font-size:12px; color:#475569; text-transform:capitalize;">
                                            {{ str_replace('_', ' ', $kas->kategori) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif
                            <td class="{{ $jenis === 'masuk' ? 'jumlah-masuk' : 'jumlah-keluar' }}">
                                {{ $jenis === 'masuk' ? '+' : '-' }}{{ number_format($kas->jumlah, 0, ',', '.') }}
                            </td>
                            <td style="color:#64748b; font-size:12px;">{{ $kas->user->name ?? '-' }}</td>
                            <td>
                                <button class="icon-btn" onclick="viewDetail({{ $kas->id }})" title="Lihat Detail">
                                    <i class="ti ti-eye"></i>
                                </button>
                                @if(in_array($authUser->role, ['super_admin', 'admin_fst', 'admin_fis']))
                                    <button class="icon-btn" onclick="editKas({{ $kas->id }})" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button class="icon-btn delete" onclick="deleteKas({{ $kas->id }})" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $jenis === 'masuk' ? 9 : 8 }}" style="text-align:center; padding:40px; color:#9ca3af;">
                                <i class="ti ti-wallet" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                                Belum ada data kas {{ strtolower($title) }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pag-wrap">
            <div>
                Menampilkan <b>{{ $kasList->firstItem() ?? 0 }}</b>-<b>{{ $kasList->lastItem() ?? 0 }}</b>
                dari <b>{{ $kasList->total() }}</b> data
            </div>
            <div style="display:flex; gap:6px;">
                {{ $kasList->links() }}
            </div>
        </div>
    </div>
</div>
