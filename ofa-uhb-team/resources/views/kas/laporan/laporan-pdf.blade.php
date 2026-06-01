<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Kas</title>
    <style>
        * { box-sizing: border-box; }
        @page { margin: 20px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; color: #1e293b; margin: 0 0 5px 0; }
        .subtitle { font-size: 12px; color: #64748b; margin: 0; }
        
        .filter-info { margin-bottom: 20px; font-size: 11px; }
        .filter-info table { width: 100%; }
        .filter-info td { padding: 2px 0; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px; }
        table.data-table th, table.data-table td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        table.data-table th { background-color: #f8fafc; font-weight: bold; color: #475569; text-transform: uppercase; font-size: 10px; }
        table.data-table tbody tr:nth-child(even) { background-color: #fcfcfc; }
        
        .summary-box { width: 100%; border-collapse: collapse; margin-top: 20px; page-break-inside: avoid; }
        .summary-box td { padding: 10px; border: 1px solid #e2e8f0; width: 33.33%; text-align: center; }
        .summary-label { font-size: 11px; color: #64748b; text-transform: uppercase; margin-bottom: 4px; display: block; }
        .summary-value { font-size: 16px; font-weight: bold; }
        
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .text-green { color: #059669; }
        .text-red { color: #dc2626; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">LAPORAN TRANSAKSI KAS</h1>
        <p class="subtitle">OFA - Universitas Harapan Bangsa</p>
    </div>

    <div class="filter-info">
        <table>
            <tr>
                <td width="100"><strong>Dicetak Pada</strong></td>
                <td width="10">:</td>
                <td>{{ now()->translatedFormat('d F Y H:i') }}</td>
                <td width="100"><strong>Oleh</strong></td>
                <td width="10">:</td>
                <td>{{ auth()->user()->name }}</td>
            </tr>
            <tr>
                <td><strong>Filter Tanggal</strong></td>
                <td>:</td>
                <td>
                    @if($request->tanggal_awal || $request->tanggal_akhir)
                        {{ $request->tanggal_awal ? \Carbon\Carbon::parse($request->tanggal_awal)->translatedFormat('d/m/Y') : '-' }} s/d {{ $request->tanggal_akhir ? \Carbon\Carbon::parse($request->tanggal_akhir)->translatedFormat('d/m/Y') : '-' }}
                    @else
                        Semua Waktu
                    @endif
                </td>
                <td><strong>Bulan/Tahun</strong></td>
                <td>:</td>
                <td>
                    {{ $request->bulan ? date('F', mktime(0, 0, 0, $request->bulan, 10)) : 'Semua Bulan' }} / 
                    {{ $request->tahun ?: 'Semua Tahun' }}
                </td>
            </tr>
            <tr>
                <td><strong>Jenis Transaksi</strong></td>
                <td>:</td>
                <td>{{ $request->jenis ? ucfirst($request->jenis) : 'Semua Jenis' }}</td>
                <td><strong>Kategori</strong></td>
                <td>:</td>
                <td>{{ $request->kategori ?: 'Semua Kategori' }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="80">Tanggal</th>
                <th width="60">Jenis</th>
                <th width="80">Kategori</th>
                <th>Keterangan</th>
                <th width="100">Fakultas / Dosen</th>
                <th width="90" class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kasList as $index => $kas)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($kas->tanggal)->format('d/m/Y') }}</td>
                <td>
                    <span class="{{ $kas->jenis == 'masuk' ? 'text-green' : 'text-red' }}">
                        {{ strtoupper($kas->jenis) }}
                    </span>
                </td>
                <td>{{ $kas->kategori ?? '-' }}</td>
                <td>{{ $kas->keterangan }}</td>
                <td>
                    {{ $kas->fakultas->nama_fakultas ?? '-' }}<br>
                    <span style="font-size:9px; color:#64748b;">{{ $kas->dosen->nama_lengkap ?? '' }}</span>
                </td>
                <td class="text-right">
                    <span class="{{ $kas->jenis == 'masuk' ? 'text-green' : 'text-red' }}">
                        {{ number_format($kas->jumlah, 0, ',', '.') }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada data transaksi yang sesuai dengan filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-box">
        <tr>
            <td style="background-color: #d1fae5; border-color: #6ee7b7;">
                <span class="summary-label">Total Kas Masuk</span>
                <span class="summary-value text-green">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
            </td>
            <td style="background-color: #fee2e2; border-color: #fca5a5;">
                <span class="summary-label">Total Kas Keluar</span>
                <span class="summary-value text-red">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
            </td>
            <td style="background-color: #dbeafe; border-color: #93c5fd;">
                <span class="summary-label">Saldo</span>
                <span class="summary-value {{ $saldo >= 0 ? 'text-green' : 'text-red' }}">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

</body>
</html>
