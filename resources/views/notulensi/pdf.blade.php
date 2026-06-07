<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notulensi Rapat — {{ $notulensi->judul }}</title>
    <style>
        * { box-sizing: border-box; }
        @page { margin: 30px 40px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 0; line-height: 1.5; }
        
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563eb; padding-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #1e293b; margin: 0 0 5px 0; text-transform: uppercase; }
        .subtitle { font-size: 13px; color: #64748b; margin: 0; }
        
        .info-table { width: 100%; margin-bottom: 20px; font-size: 12px; }
        .info-table td { padding: 4px 0; vertical-align: top; }
        
        .section-title { font-size: 14px; font-weight: bold; color: #1e293b; border-bottom: 1px solid #e2e8f0; margin-top: 20px; margin-bottom: 10px; padding-bottom: 5px; }
        
        .content-box { background-color: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; white-space: pre-wrap; font-size: 12px; margin-bottom: 15px; }
        .resume-box { background-color: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 12px; margin-bottom: 15px; }
        
        table.peserta-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px; }
        table.peserta-table th, table.peserta-table td { border: 1px solid #e2e8f0; padding: 8px; text-align: center; }
        table.peserta-table th { background-color: #f1f5f9; font-weight: bold; color: #475569; }
        table.peserta-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        
        .text-center { text-align: center !important; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">NOTULENSI RAPAT</h1>
        <p class="subtitle">{{ strtoupper($notulensi->judul) }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="120"><strong>Hari/Tanggal</strong></td>
            <td width="10">:</td>
            <td>{{ \Carbon\Carbon::parse($notulensi->tanggal)->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Tempat</strong></td>
            <td>:</td>
            <td>{{ $notulensi->tempat }}</td>
        </tr>
        <tr>
            <td><strong>Fakultas</strong></td>
            <td>:</td>
            <td>{{ $notulensi->fakultas->nama_fakultas ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Dibuat Oleh</strong></td>
            <td>:</td>
            <td>{{ $notulensi->user->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Agenda Rapat</div>
    <div class="content-box">{{ $notulensi->agenda_rapat ?? '-' }}</div>

    <div class="section-title">Resume Rapat</div>
    <div class="resume-box">{!! $notulensi->agenda !!}</div>

    @if($notulensi->tindak_lanjut)
    <div class="section-title">Tindak Lanjut / Hasil Rapat</div>
    <div class="content-box">{{ $notulensi->tindak_lanjut }}</div>
    @endif

    <div class="section-title">Daftar Hadir Peserta ({{ $notulensi->dosens->count() }} orang)</div>
    <table class="peserta-table">
        <thead>
            <tr>
                <th width="40" class="text-center">No</th>
                <th>Nama Peserta</th>
                <th width="150">NIDN</th>
                <th width="200">Program Studi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notulensi->dosens as $index => $dosen)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $dosen->nama_lengkap }}</td>
                <td>{{ $dosen->nidn ?? '-' }}</td>
                <td>{{ $dosen->prodi->nama_prodi ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada peserta yang didaftarkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- DOKUMENTASI FOTO -->
    @if($notulensi->dokumentasiNotulensi && $notulensi->dokumentasiNotulensi->count() > 0)
        <div style="page-break-before: always; margin-top:20px;">
            <div class="section-title" style="text-align:center; font-size:14px;">DOKUMENTASI FOTO</div>
            <div style="text-align: center; margin-top: 15px;">
                @foreach($notulensi->dokumentasiNotulensi as $dok)
                    @php
                        $path = public_path('storage/dokumentasi-notulensi/' . $dok->nama_file);
                        if (!file_exists($path)) {
                            $path = storage_path('app/public/dokumentasi-notulensi/' . $dok->nama_file);
                        }
                    @endphp
                    @if(file_exists($path))
                        <img src="{{ $path }}" style="max-width: 80%; max-height: 280px; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px; background: #fff;" />
                    @endif
                @endforeach
            </div>
        </div>
    @endif

</body>
</html>
