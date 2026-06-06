<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tagihan Dosen</title>
    <style>
        * { box-sizing: border-box; }
        @page { margin: 20px 25px; size: A4 landscape; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 15px; }
        .header { text-align: center; margin-bottom: 18px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .title { font-size: 17px; font-weight: bold; color: #1e293b; margin: 0 0 5px 0; text-transform: uppercase; }
        .subtitle { font-size: 11px; color: #64748b; margin: 0; }

        .filter-info { margin-bottom: 15px; font-size: 10.5px; }
        .filter-info table { width: 100%; }
        .filter-info td { padding: 2px 0; vertical-align: top; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10.5px; }
        table.data-table th, table.data-table td { border: 1px solid #e2e8f0; padding: 7px 10px; text-align: center !important; }
        table.data-table th { background-color: #f1f5f9; font-weight: bold; color: #475569; text-transform: uppercase; font-size: 9.5px; letter-spacing: 0.03em; }
        table.data-table tbody tr:nth-child(even) { background-color: #fafafa; }

        .summary-box { width: 100%; border-collapse: collapse; margin-top: 15px; page-break-inside: avoid; }
        .summary-box td { padding: 10px; border: 1px solid #e2e8f0; width: 33.33%; text-align: center; }
        .summary-label { font-size: 10px; color: #64748b; text-transform: uppercase; margin-bottom: 4px; display: block; }
        .summary-value { font-size: 15px; font-weight: bold; }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .text-left { text-align: left !important; }
        .text-green { color: #059669; }
        .text-red { color: #dc2626; }

        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .status-lunas { background: #d1fae5; color: #065f46; }
        .status-belum { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">LAPORAN TAGIHAN DOSEN</h1>
        <p class="subtitle">OFA - Universitas Harapan Bangsa</p>
    </div>

    <div class="filter-info">
        <table>
            <tr>
                <td width="90"><strong>Dicetak Pada</strong></td>
                <td width="10">:</td>
                <td>{{ now()->translatedFormat('d F Y H:i') }}</td>
                <td width="90"><strong>Dicetak Oleh</strong></td>
                <td width="10">:</td>
                <td>{{ auth()->user()->name }}</td>
            </tr>
            <tr>
                <td><strong>Status Bayar</strong></td>
                <td>:</td>
                <td>
                    @if($request->status == 'lunas')
                        Lunas
                    @elseif($request->status == 'belum_lunas')
                        Belum Lunas
                    @else
                        Semua Status
                    @endif
                </td>
                <td><strong>Periode</strong></td>
                <td>:</td>
                <td>
                    {{ $request->bulan ? ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$request->bulan - 1] : 'Semua Bulan' }} / 
                    {{ $request->tahun ?: 'Semua Tahun' }}
                </td>
            </tr>
            <tr>
                <td><strong>Fakultas</strong></td>
                <td>:</td>
                <td>
                    @if($request->fakultas_id)
                        @php $fak = \App\Models\Fakultas::find($request->fakultas_id); @endphp
                        {{ $fak ? $fak->nama_fakultas : 'Semua Fakultas' }}
                    @else
                        Semua Fakultas
                    @endif
                </td>
                <td><strong>Pencarian</strong></td>
                <td>:</td>
                <td>{{ $request->search ?: '-' }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="160">Nama Dosen</th>
                <th width="150">Fakultas</th>
                <th width="110">Periode</th>
                <th width="110" class="text-right">Tagihan (Rp)</th>
                <th width="110" class="text-right">Dibayar (Rp)</th>
                <th width="90" class="text-right">Sisa (Rp)</th>
                <th width="80" class="text-center">Status</th>
                <th width="90" class="text-center">Tgl Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tagihanList as $index => $tagihan)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-left" style="font-weight: 500;">{{ $tagihan->dosen->nama_lengkap ?? '-' }}</td>
                <td class="text-left">{{ $tagihan->fakultas->nama_fakultas ?? '-' }}</td>
                <td class="text-center">
                    {{ ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][($tagihan->bulan ?? 1) - 1] }} {{ $tagihan->tahun }}
                </td>
                <td class="text-right">{{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($tagihan->dibayar_amount, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format(max(0, $tagihan->jumlah - $tagihan->dibayar_amount), 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="status-badge {{ $tagihan->status === 'lunas' ? 'status-lunas' : 'status-belum' }}">
                        {{ $tagihan->status === 'lunas' ? 'Lunas' : 'Belum' }}
                    </span>
                </td>
                <td class="text-center">
                    {{ $tagihan->dibayar_tanggal ? \Carbon\Carbon::parse($tagihan->dibayar_tanggal)->format('d/m/Y') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 20px; color: #9ca3af;">Tidak ada data tagihan yang sesuai dengan filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-box">
        <tr>
            <td style="background-color: #f8fafc; border-color: #cbd5e1;">
                <span class="summary-label">Total Tagihan</span>
                <span class="summary-value" style="color: #475569;">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
            </td>
            <td style="background-color: #d1fae5; border-color: #6ee7b7;">
                <span class="summary-label">Total Terbayar</span>
                <span class="summary-value text-green">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
            </td>
            <td style="background-color: #fee2e2; border-color: #fca5a5;">
                <span class="summary-label">Sisa Tagihan</span>
                <span class="summary-value text-red">Rp {{ number_format($totalSisa, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>
</body>
</html>
