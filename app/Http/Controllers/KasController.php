<?php

namespace App\Http\Controllers;

use App\Models\KasTransaction;
use App\Models\KasTagihan;
use App\Models\Dosen;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KasController extends Controller
{
    use AuthorizesRequests;

    // ── INDEX KAS (Masuk / Keluar) ─────────────────────────

    public function index(Request $request, string $jenis = 'masuk')
    {
        $this->authorize('viewAny', KasTransaction::class);

        $user  = auth()->user();
        $query = KasTransaction::with(['fakultas', 'user'])
            ->where('jenis', $jenis);

        // Role-based filter
        if (in_array($user->role, ['admin_fst', 'admin_fis'])) {
            $query->where('fakultas_id', $user->fakultas_id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter by fakultas
        if ($request->filled('fakultas_id')) {
            $query->where('fakultas_id', $request->fakultas_id);
        }

        // Filter by tanggal range
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        $kasList = $query->latest('tanggal')->paginate(10)->withQueryString();

        $fakultasList = ($user->role === 'super_admin' || $user->role === 'kepala_unit')
            ? Fakultas::all()
            : Fakultas::where('id', $user->fakultas_id)->get();

        $dosensList = collect();
        if ($jenis === 'masuk') {
            if ($user->role === 'super_admin') {
                $dosensList = \App\Models\Dosen::with(['prodi', 'prodi.fakultas'])->orderBy('nama_lengkap')->get();
            } else {
                $dosensList = \App\Models\Dosen::with(['prodi', 'prodi.fakultas'])
                    ->whereHas('prodi', fn($q) => $q->where('fakultas_id', $user->fakultas_id))
                    ->orderBy('nama_lengkap')->get();
            }
        }

        $title = $jenis === 'masuk' ? 'Masuk' : 'Keluar';

        return view('kas.index', compact('kasList', 'fakultasList', 'dosensList', 'jenis', 'title'));
    }

    // ── STORE KAS ─────────────────────────────────────────

    public function store(Request $request)
    {
        $this->authorize('create', KasTransaction::class);

        $user = auth()->user();
manajemen-kas-oza
        $jenis = $request->input('jenis', 'masuk');

        $jenis = $request->jenis;
main

        $validated = $request->validate([
            'jenis'        => 'required|in:masuk,keluar',
            'jumlah'       => 'required|numeric|min:1',
            'tanggal'      => 'required|date',
            'kategori'     => 'nullable|string',
            'keterangan'   => 'required|string|max:255',
            'resume_rapat' => 'nullable|string',
            'fakultas_id'  => 'nullable|exists:fakultas,id',
            'dosen_id'     => 'nullable|exists:dosens,id',
            'bukti_foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fakultasId = ($user->role === 'super_admin' && $request->filled('fakultas_id'))
            ? $request->fakultas_id
            : $user->fakultas_id;

        $tabungan = 0;
        $sosial = 0;
        if ($jenis === 'masuk' && $request->filled('dosen_id')) {
            $tabungan = round($validated['jumlah'] * 0.3333);
            $sosial = $validated['jumlah'] - $tabungan;
        }

        $buktiFotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $buktiFotoPath = $request->file('bukti_foto')->store('kas_bukti', 'public');
        }

        $kas = KasTransaction::create([
manajemen-kas-oza
            'jenis'        => $validated['jenis'],
            'jumlah'       => $validated['jumlah'],
            'tanggal'      => $validated['tanggal'],
            'keterangan'   => $validated['keterangan'],
            'resume_rapat' => $validated['resume_rapat'] ?? null,
            'fakultas_id'  => $fakultasId,
            'user_id'      => $user->id,

            'jenis'        => $jenis,
            'kategori'     => $jenis === 'keluar' ? ($validated['kategori'] ?? null) : null,
            'jumlah'       => $validated['jumlah'],
            'tabungan'     => $tabungan,
            'uang_sosial'  => $sosial,
            'tanggal'       => $validated['tanggal'],
            'keterangan'    => $validated['keterangan'],
            'bukti_foto'    => $buktiFotoPath,
            'fakultas_id'   => $fakultasId,
            'dosen_id'      => $validated['dosen_id'] ?? null,
            'user_id'       => $user->id,
main
        ]);

        // ── Notifikasi ───────────────────────────────────────
        $jumlahFmt = number_format($validated['jumlah'], 0, ',', '.');
        if ($jenis === 'masuk') {
            \App\Helpers\NotifikasiHelper::notifKasMasuk(
                $validated['keterangan'],
                $jumlahFmt,
                route('kas.masuk')
            );
        } else {
            \App\Helpers\NotifikasiHelper::notifKasKeluar(
                $validated['keterangan'],
                $jumlahFmt,
                route('kas.keluar')
            );
        }

        return response()->json([
            'success' => true,
            'message' => ucfirst($jenis) . ' kas berhasil disimpan.',
            'data'    => $kas,
        ]);
    }

    // ── SHOW KAS ──────────────────────────────────────────

    public function show(string $id)
    {
        $kas = KasTransaction::with(['fakultas', 'user'])->findOrFail($id);
        $this->authorize('view', $kas);
        return response()->json($kas);
    }

    // ── UPDATE KAS ─────────────────────────────────────────

    public function update(Request $request, string $id)
    {
        $this->authorize('update', KasTransaction::findOrFail($id));

        $user = auth()->user();
        $jenis = $request->jenis;

        $validated = $request->validate([
            'jumlah'       => 'required|numeric|min:1',
            'tanggal'      => 'required|date',
            'kategori'     => 'nullable|string',
            'keterangan'   => 'required|string|max:255',
            'resume_rapat' => 'nullable|string',
            'fakultas_id'  => 'nullable|exists:fakultas,id',
            'dosen_id'     => 'nullable|exists:dosens,id',
            'bukti_foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $kas = KasTransaction::findOrFail($id);
        $fakultasId = ($user->role === 'super_admin' && $request->filled('fakultas_id'))
            ? $request->fakultas_id
            : $kas->fakultas_id;

        $tabungan = 0;
        $sosial = 0;
        if ($jenis === 'masuk' && $request->filled('dosen_id')) {
            $tabungan = round($validated['jumlah'] * 0.3333);
            $sosial = $validated['jumlah'] - $tabungan;
        }

        $buktiFotoPath = $kas->bukti_foto;
        if ($request->hasFile('bukti_foto')) {
            if ($kas->bukti_foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($kas->bukti_foto);
            }
            $buktiFotoPath = $request->file('bukti_foto')->store('kas_bukti', 'public');
        }

        $kas->update([
            'kategori'     => $jenis === 'keluar' ? ($validated['kategori'] ?? null) : null,
            'jumlah'       => $validated['jumlah'],
manajemen-kas-oza
            'tanggal'      => $validated['tanggal'],
            'keterangan'   => $validated['keterangan'],
            'resume_rapat' => $validated['resume_rapat'] ?? null,
            'fakultas_id'  => $fakultasId,

            'tabungan'     => $tabungan,
            'uang_sosial'  => $sosial,
            'tanggal'       => $validated['tanggal'],
            'keterangan'    => $validated['keterangan'],
            'bukti_foto'    => $buktiFotoPath,
            'fakultas_id'   => $fakultasId,
            'dosen_id'      => $validated['dosen_id'] ?? null,
main
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data kas berhasil diperbarui.',
        ]);
    }

    // ── DESTROY KAS ─────────────────────────────────────────

    public function destroy(string $id)
    {
        $kas = KasTransaction::findOrFail($id);
        $this->authorize('delete', $kas);
        $kas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kas berhasil dihapus.',
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // ── TAGIHAN ─────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════

    public function tagihan(Request $request)
    {
        $this->authorize('viewAny', KasTagihan::class);

        $user  = auth()->user();
        $query = KasTagihan::with(['dosen', 'dosen.prodi', 'fakultas']);

        // Role-based filter
        if (in_array($user->role, ['admin_fst', 'admin_fis'])) {
            $query->where('fakultas_id', $user->fakultas_id);
        } elseif ($user->role === 'dosen') {
            $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
            if ($dosen) {
                $query->where('dosen_id', $dosen->id);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('dosen', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        // Filter
        if ($request->filled('fakultas_id')) {
            $query->where('fakultas_id', $request->fakultas_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $tagihanList = $query->latest()->paginate(10)->withQueryString();

        $fakultasList = ($user->role === 'super_admin' || $user->role === 'kepala_unit')
            ? Fakultas::all()
            : Fakultas::where('id', $user->fakultas_id)->get();

        $dosenList = ($user->role === 'super_admin' || $user->role === 'kepala_unit')
            ? Dosen::with('prodi')->orderBy('nama_lengkap')->get()
            : Dosen::whereHas('prodi', fn($q) => $q->where('fakultas_id', $user->fakultas_id))
                   ->with('prodi')->orderBy('nama_lengkap')->get();

        return view('kas.tagihan.index', compact('tagihanList', 'fakultasList', 'dosenList'));
    }

    // ── STORE TAGIHAN ───────────────────────────────────────

    public function storeTagihan(Request $request)
    {
        $this->authorize('create', KasTagihan::class);

        $user = auth()->user();

        $validated = $request->validate([
            'dosen_id'           => 'required|exists:dosens,id',
            'bulan'              => 'required|integer|min:1|max:12',
            'tahun'              => 'required|integer|min:2020|max:2100',
            'jumlah'             => 'required|numeric|min:1',
            'tanggal_jatuh_tempo' => 'nullable|date',
        ]);

        // Cek duplikat
        $exists = KasTagihan::where('dosen_id', $validated['dosen_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan untuk dosen ini pada periode tersebut sudah ada.',
            ], 422);
        }

        $dosen = Dosen::with('prodi')->find($validated['dosen_id']);
        $fakultasId = $dosen->prodi->fakultas_id ?? ($user->fakultas_id ?? null);

        $tagihan = KasTagihan::create([
            'dosen_id'           => $validated['dosen_id'],
            'fakultas_id'        => $fakultasId,
            'bulan'              => $validated['bulan'],
            'tahun'              => $validated['tahun'],
            'jumlah'             => $validated['jumlah'],
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'] ?? null,
            'user_id'            => $user->id,
        ]);

        if ($dosen && $dosen->user_id) {
            $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            $bln = $namaBulan[$tagihan->bulan - 1];
            $jumlahFmt = number_format($tagihan->jumlah, 0, ',', '.');

            // Notif ke dosen ybs, admin kas, super_admin, kepala_unit
            \App\Helpers\NotifikasiHelper::notifTagihanDibuat(
                $dosen->user_id,
                $dosen->nama_lengkap,
                $bln,
                $tagihan->tahun,
                $jumlahFmt,
                route('dashboard'),  // url untuk dosen (arahkan ke dashboard)
                route('kas.tagihan') // url untuk admin
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil dibuat.',
            'data'    => $tagihan,
        ]);
    }

    // ── SHOW TAGIHAN ───────────────────────────────────────

    public function showTagihan(string $id)
    {
        $tagihan = KasTagihan::with(['dosen', 'dosen.prodi', 'fakultas', 'user'])->findOrFail($id);
        $this->authorize('view', $tagihan);
        return response()->json($tagihan);
    }

    // ── BAYAR TAGIHAN ──────────────────────────────────────

    public function bayarTagihan(Request $request, string $id)
    {
        $user = auth()->user();
        $tagihan = KasTagihan::with('dosen')->findOrFail($id);
        $this->authorize('pay', $tagihan);

        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'keterangan'    => 'nullable|string|max:255',
            'bukti_foto'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $jumlahBayar = (float) $validated['jumlah_bayar'];
        $sisaTagihan = (float) $tagihan->jumlah - (float) $tagihan->dibayar_amount;

        if ($jumlahBayar > $sisaTagihan + 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah bayar tidak boleh melebihi sisa tagihan (' . number_format($sisaTagihan, 0, ',', '.') . ').',
            ], 422);
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $filename = time() . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('kas_bukti', $filename, 'public');
        }

        DB::transaction(function () use ($tagihan, $jumlahBayar, $validated, $user, $buktiPath) {
            // Update tagihan
            $tagihan->dibayar_amount = (float) $tagihan->dibayar_amount + $jumlahBayar;
            $tagihan->dibayar_tanggal = $validated['tanggal_bayar'];

            if ($tagihan->dibayar_amount >= (float) $tagihan->jumlah - 0.01) {
                $tagihan->status = 'lunas';
            }

            $tagihan->save();

            // Buat transaksi kas masuk
            $keterangan = $validated['keterangan']
                ?? "Pembayaran tagihan kas {$tagihan->bulan}/{$tagihan->tahun}";
            $keterangan .= " — {$tagihan->dosen->nama_lengkap}";

            $tabungan = round($jumlahBayar * 0.3333);
            $sosial = $jumlahBayar - $tabungan;

            KasTransaction::create([
                'jenis'        => 'masuk',
                'jumlah'       => $jumlahBayar,
                'tabungan'     => $tabungan,
                'uang_sosial'  => $sosial,
                'tanggal'      => $validated['tanggal_bayar'],
                'keterangan'   => $keterangan,
                'fakultas_id'  => $tagihan->fakultas_id,
                'dosen_id'     => $tagihan->dosen_id,
                'user_id'      => $user->id,
                'referensi_id' => $tagihan->id,
                'referensi_type' => KasTagihan::class,
                'bukti_foto'   => $buktiPath,
            ]);

            if ($tagihan->dosen && $tagihan->dosen->user_id) {
                $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                $bln = $namaBulan[$tagihan->bulan - 1];
                $jumlahFmt = number_format($jumlahBayar, 0, ',', '.');

                // Notif ke dosen ybs + admin kas, super_admin, kepala_unit
                \App\Helpers\NotifikasiHelper::notifPembayaranTagihan(
                    $tagihan->dosen->user_id,
                    $tagihan->dosen->nama_lengkap,
                    $bln,
                    $tagihan->tahun,
                    $jumlahFmt,
                    route('dashboard'),      // url dosen → ke dashboard
                    route('kas.tagihan')     // url admin → ke tagihan
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran tagihan berhasil disimpan.',
        ]);
    }

    // ── DESTROY TAGIHAN ────────────────────────────────────

    public function destroyTagihan(string $id)
    {
        $tagihan = KasTagihan::findOrFail($id);
        $this->authorize('delete', $tagihan);
        $tagihan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil dihapus.',
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // ── LAPORAN ─────────────────────────────────────────────
    // ═══════════════════════════════════════════════════════════

    public function laporan(Request $request)
    {
        $user = auth()->user();

        $tahun    = $request->filled('tahun') ? $request->tahun : now()->year;
        $fakId    = $request->filled('fakultas_id') ? $request->fakultas_id : null;

        // Total keseluruhan
        $baseQuery = KasTransaction::query();
        if (in_array($user->role, ['admin_fst', 'admin_fis'])) {
            $baseQuery->where('fakultas_id', $user->fakultas_id);
        } elseif ($fakId) {
            $baseQuery->where('fakultas_id', $fakId);
        }

        $totalMasuk  = (clone $baseQuery)->masuk()->whereYear('tanggal', $tahun)->sum('jumlah');
        $totalKeluar = (clone $baseQuery)->keluar()->whereYear('tanggal', $tahun)->sum('jumlah');
        $saldo       = $totalMasuk - $totalKeluar;

        // Breakdown per bulan
        $bulanQuery = (clone $baseQuery)->whereYear('tanggal', $tahun);
        $reak = DB::table('kas_transactions')
            ->selectRaw("
                MONTH(tanggal) as bulan_num,
                SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END) as total_masuk,
                SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) as total_keluar
            ")
            ->whereYear('tanggal', $tahun);

        if (in_array($user->role, ['admin_fst', 'admin_fis'])) {
            $reak->where('fakultas_id', $user->fakultas_id);
        } elseif ($fakId) {
            $reak->where('fakultas_id', $fakId);
        }

        $reakBulan = $reak->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy('bulan_num')
            ->get();

        $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                   7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                      5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                      9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $reakByBulan = collect(range(1, 12))->map(function ($bulan) use ($reakBulan, $romawi, $namaBulan) {
            $data = $reakBulan->firstWhere('bulan_num', $bulan);
            $masuk  = $data->total_masuk ?? 0;
            $keluar = $data->total_keluar ?? 0;
            return [
                'bulan'       => $bulan,
                'bulan_romawi'=> $romawi[$bulan],
                'bulan_nama'  => $namaBulan[$bulan],
                'total_masuk' => $masuk,
                'total_keluar'=> $keluar,
                'saldo'       => $masuk - $keluar,
            ];
        });

        $fakultasList = ($user->role === 'super_admin' || $user->role === 'kepala_unit')
            ? Fakultas::all()
            : Fakultas::where('id', $user->fakultas_id)->get();

        return view('kas.laporan.index', compact(
            'totalMasuk', 'totalKeluar', 'saldo',
            'reakByBulan', 'tahun', 'fakId', 'fakultasList'
        ));
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();

        $query = KasTransaction::with(['fakultas', 'user', 'dosen']);

        // Role-based filter
        if (in_array($user->role, ['admin_kas_fst', 'admin_kas_fis'])) {
            $query->where('fakultas_id', $user->fakultas_id);
        } elseif ($request->filled('fakultas_id')) {
            $query->where('fakultas_id', $request->fakultas_id);
        }

        // Apply filters
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $kasList = $query->orderBy('tanggal', 'asc')->get();

        $totalMasuk = $kasList->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $kasList->where('jenis', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        $pdf = Pdf::loadView('kas.laporan.laporan-pdf', compact('kasList', 'totalMasuk', 'totalKeluar', 'saldo', 'request'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Kas-' . date('Y-m-d') . '.pdf');
    }
}