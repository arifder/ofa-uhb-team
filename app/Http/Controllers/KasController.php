<?php

namespace App\Http\Controllers;

use App\Models\KasTransaction;
use App\Models\KasTagihan;
use App\Models\Dosen;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    // ── INDEX KAS (Masuk / Keluar) ─────────────────────────

    public function index(Request $request, string $jenis = 'masuk')
    {
        $user  = auth()->user();
        $query = KasTransaction::with(['fakultas', 'user'])
            ->where('jenis', $jenis);

        // Role-based filter
        if (in_array($user->role, ['admin_kas_fst', 'admin_kas_fis'])) {
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

        $title = $jenis === 'masuk' ? 'Kas Masuk' : 'Kas Keluar';

        return view('kas.index', compact('kasList', 'fakultasList', 'jenis', 'title'));
    }

    // ── STORE KAS ─────────────────────────────────────────

    public function store(Request $request, string $jenis)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'jumlah'      => 'required|numeric|min:1',
            'tanggal'      => 'required|date',
            'keterangan'   => 'required|string|max:255',
            'fakultas_id'  => 'nullable|exists:fakultas,id',
        ]);

        $fakultasId = ($user->role === 'super_admin' && $request->filled('fakultas_id'))
            ? $request->fakultas_id
            : $user->fakultas_id;

        $kas = KasTransaction::create([
            'jenis'        => $jenis,
            'jumlah'       => $validated['jumlah'],
            'tanggal'       => $validated['tanggal'],
            'keterangan'    => $validated['keterangan'],
            'fakultas_id'   => $fakultasId,
            'user_id'       => $user->id,
        ]);

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
        return response()->json($kas);
    }

    // ── UPDATE KAS ─────────────────────────────────────────

    public function update(Request $request, string $id, string $jenis)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'jumlah'      => 'required|numeric|min:1',
            'tanggal'      => 'required|date',
            'keterangan'   => 'required|string|max:255',
            'fakultas_id'  => 'nullable|exists:fakultas,id',
        ]);

        $kas = KasTransaction::findOrFail($id);
        $fakultasId = ($user->role === 'super_admin' && $request->filled('fakultas_id'))
            ? $request->fakultas_id
            : $kas->fakultas_id;

        $kas->update([
            'jumlah'       => $validated['jumlah'],
            'tanggal'       => $validated['tanggal'],
            'keterangan'    => $validated['keterangan'],
            'fakultas_id'   => $fakultasId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data kas berhasil diperbarui.',
        ]);
    }

    // ── DESTROY KAS ─────────────────────────────────────────

    public function destroy(string $id)
    {
        KasTransaction::findOrFail($id)->delete();

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
        $user  = auth()->user();
        $query = KasTagihan::with(['dosen', 'dosen.prodi', 'fakultas']);

        // Role-based filter
        if (in_array($user->role, ['admin_kas_fst', 'admin_kas_fis'])) {
            $query->where('fakultas_id', $user->fakultas_id);
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
        return response()->json($tagihan);
    }

    // ── BAYAR TAGIHAN ──────────────────────────────────────

    public function bayarTagihan(Request $request, string $id)
    {
        $user = auth()->user();
        $tagihan = KasTagihan::with('dosen')->findOrFail($id);

        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'keterangan'    => 'nullable|string|max:255',
        ]);

        $jumlahBayar = (float) $validated['jumlah_bayar'];
        $sisaTagihan = (float) $tagihan->jumlah - (float) $tagihan->dibayar_amount;

        if ($jumlahBayar > $sisaTagihan + 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah bayar tidak boleh melebihi sisa tagihan (' . number_format($sisaTagihan, 0, ',', '.') . ').',
            ], 422);
        }

        DB::transaction(function () use ($tagihan, $jumlahBayar, $validated, $user) {
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

            KasTransaction::create([
                'jenis'       => 'masuk',
                'jumlah'      => $jumlahBayar,
                'tanggal'      => $validated['tanggal_bayar'],
                'keterangan'   => $keterangan,
                'fakultas_id'  => $tagihan->fakultas_id,
                'user_id'      => $user->id,
                'referensi_id' => $tagihan->id,
                'referensi_type' => KasTagihan::class,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran tagihan berhasil disimpan.',
        ]);
    }

    // ── DESTROY TAGIHAN ────────────────────────────────────

    public function destroyTagihan(string $id)
    {
        KasTagihan::findOrFail($id)->delete();

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
        if (in_array($user->role, ['admin_kas_fst', 'admin_kas_fis'])) {
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

        if (in_array($user->role, ['admin_kas_fst', 'admin_kas_fis'])) {
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
            : Faisal::where('id', $user->fakultas_id)->get();

        return view('kas.laporan.index', compact(
            'totalMasuk', 'totalKeluar', 'saldo',
            'reakByBulan', 'tahun', 'fakId', 'fakultasList'
        ));
    }
}