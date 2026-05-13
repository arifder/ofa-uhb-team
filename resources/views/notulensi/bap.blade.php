<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pertemuan — {{ $notulensi->nomor_bap }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            margin: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .content {
            padding: 20px 40px;
            /* padding isi surat */
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <img src="{{ public_path('images/bap-header.png') }}" style="width:100%; display:block;" />


    <div class="content">
        <!-- NOMOR DOKUMEN -->
        <div style="text-align:right; font-size:10px; margin: 4px 0;">
            {{ $notulensi->nomor_bap }}
        </div>

        <!-- JUDUL -->
        <div style="text-align:center; font-weight:bold; margin: 16px 0; line-height:1.6;">
            BERITA ACARA PELAKSANAAN KEGIATAN<br>
            {{ strtoupper($notulensi->judul) }}<br>
            PROGRAM STUDI {{ strtoupper($notulensi->dosens->first()->prodi->nama_prodi ?? '-') }}<br>
            FAKULTAS {{ strtoupper($notulensi->fakultas->nama_fakultas ?? '-') }}<br>
            UNIVERSITAS HARAPAN BANGSA
        </div>

        <!-- INFO RAPAT -->
        <table style="width:100%; font-size:12px; margin-bottom:16px;">
            <tr>
                <td width="140">Hari/Tanggal</td>
                <td width="10">:</td>
                <td>{{ \Carbon\Carbon::parse($notulensi->tanggal)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $notulensi->tempat }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Agenda</td>
                <td style="vertical-align: top;">:</td>
                <td style="white-space:pre-wrap;">{{ $notulensi->agenda }}</td>
            </tr>
        </table>

        <!-- DAFTAR HADIR -->
        <p style="font-weight:bold; font-size:12px; margin-bottom:6px;">
            DAFTAR HADIR PESERTA
        </p>
        <table style="width:100%; border-collapse:collapse; font-size:11px;">
            <thead>
                <tr style="background:#f0f0f0;">
                    <th style="border:1px solid #000; padding:6px; width:30px;">No</th>
                    <th style="border:1px solid #000; padding:6px;">Nama</th>
                    <th style="border:1px solid #000; padding:6px; width:100px;">NIDN</th>
                    <th style="border:1px solid #000; padding:6px;">Program Studi</th>
                    <th style="border:1px solid #000; padding:6px; width:80px;">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notulensi->dosens as $i => $dosen)
                    <tr>
                        <td style="border:1px solid #000; padding:6px; text-align:center;">{{ $i + 1 }}</td>
                        <td style="border:1px solid #000; padding:6px;">{{ $dosen->nama_lengkap }}</td>
                        <td style="border:1px solid #000; padding:6px;">{{ $dosen->nidn }}</td>
                        <td style="border:1px solid #000; padding:6px;">{{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                        <td style="border:1px solid #000; padding:6px; height:40px;">
                            <span style="font-size:9px; margin-left: {{ $i % 2 !== 0 ? '40px' : '0' }};">{{ $i + 1 }}
                                .</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TINDAK LANJUT -->
        <div style="margin-top:16px; font-size:12px;">
            <p style="font-weight:bold;">Tindak Lanjut / Hasil Rapat:</p>
            <p style="white-space:pre-wrap; margin-top:6px;">{{ $notulensi->tindak_lanjut ?? '-' }}</p>
        </div>

        <!-- TTD (opsional) -->
        @if($showTtd)
            <div style="margin-top:40px; width: 100%; font-size:12px;">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="text-align:center; width:50%; vertical-align: bottom;">
                            <p style="margin: 0;">Mengetahui,</p>
                            <p style="margin: 0; margin-bottom: 60px;">Dekan
                                {{ $notulensi->fakultas->nama_fakultas ?? '-' }}</p>
                            <p
                                style="border-top:1px solid #000; padding-top:4px; display:inline-block; width: 200px; margin: 0;">
                                {{ $namaDekan ?? '_________________________' }}
                            </p>
                        </td>
                        <td style="text-align:center; width:50%; vertical-align: bottom;">
                            <p style="margin: 0; margin-bottom: 60px;">Ketua Program Studi</p>
                            <p
                                style="border-top:1px solid #000; padding-top:4px; display:inline-block; width: 200px; margin: 0;">
                                {{ $namaKaprodi ?? '_________________________' }}
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        @endif
    </div>

    <!-- FOOTER -->
    <div style="position:fixed; bottom:0; left:0; right:0;">
        <img src="{{ public_path('images/bap-footer.png') }}" style="width:100%; display:block;" />
    </div>

</body>

</html>