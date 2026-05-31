<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Notulensi;
use App\Models\PesertaRapat;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\DokumentasiNotulensi;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NotifikasiHelper;

class NotulensiController extends Controller
{
    // ── INDEX ───────────────────────────────────────────

    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Notulensi::with(['fakultas', 'user', 'pesertaRapat', 'dosens']);

        // Role-based filter
        if (in_array($user->role, ['admin_fst', 'admin_fis'])) {
            $query->fakultas($user->fakultas_id);
        } elseif ($user->role === 'dosen') {
            $dosen = Dosen::where('user_id', $user->id)->first();
            if ($dosen) {
                $query->whereHas('pesertaRapat', fn($q) => $q->where('dosen_id', $dosen->id));
            }
        }
        // super_admin & kepala_unit → no filter

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('nomor_bap', 'like', "%{$search}%");
            });
        }

        // Filter by fakultas (super_admin / kepala_unit)
        if ($request->filled('fakultas_id')) {
            $query->where('fakultas_id', $request->fakultas_id);
        }

        $notulensiList = $query->latest()->paginate(10)->withQueryString();

        // Dosen list for peserta dropdown
        if (in_array($user->role, ['admin_fst', 'admin_fis'])) {
            $dosenList = Dosen::whereHas('prodi', function ($q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id);
            })->with('prodi')->orderBy('nama_lengkap')->get();
        } else {
            $dosenList = Dosen::with('prodi')->orderBy('nama_lengkap')->get();
        }

        $fakultasList = Fakultas::all();

        return view('notulensi.index', compact('notulensiList', 'dosenList', 'fakultasList'));
    }

    // ── STORE ───────────────────────────────────────────

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'tempat'        => 'required|string|max:255',
            'agenda'        => 'required|string',
            'tindak_lanjut' => 'nullable|string',
            'peserta'       => 'required|array|min:1',
            'peserta.*'     => 'exists:dosens,id',
            'fakultas_id'   => 'nullable|exists:fakultas,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Determine fakultas_id
        $fakultasId = ($user->role === 'super_admin' && $request->filled('fakultas_id'))
            ? $request->fakultas_id
            : $user->fakultas_id;

        $nomorBap = Notulensi::generateNomorBap($fakultasId);

        $notulensi = Notulensi::create([
            'judul'         => $validated['judul'],
            'tanggal'       => $validated['tanggal'],
            'tempat'        => $validated['tempat'],
            'agenda'        => $validated['agenda'],
            'tindak_lanjut' => $validated['tindak_lanjut'] ?? null,
            'fakultas_id'   => $fakultasId,
            'user_id'       => $user->id,
            'nomor_bap'     => $nomorBap,
        ]);

        foreach ($request->peserta as $dosenId) {
            PesertaRapat::create([
                'notulensi_id' => $notulensi->id,
                'dosen_id'     => $dosenId,
            ]);
        }

        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('dokumentasi-notulensi', $namaFile, 'public');
                DokumentasiNotulensi::create([
                    'notulensi_id' => $notulensi->id,
                    'nama_file'    => $namaFile,
                    'path_file'    => $path,
                ]);
            }
        }

        // ── Notifikasi ───────────────────────────────────────
        $notifUrl = route('notulensi.index');

        NotifikasiHelper::notifNotulensi(
            'Notulensi Baru',
            'Notulensi "' . $notulensi->judul . '" telah ditambahkan.',
            $notifUrl
        );

        NotifikasiHelper::notifDosenPeserta(
            $request->peserta,
            'Anda Terdaftar sebagai Peserta Rapat',
            'Anda terdaftar dalam rapat "' . $notulensi->judul . '" pada ' . $notulensi->tanggal->format('d/m/Y') . '.',
            $notifUrl
        );

        return response()->json([
            'success' => true,
            'message' => 'Notulensi berhasil disimpan.',
            'data'    => $notulensi,
        ]);
    }

    // ── SHOW ────────────────────────────────────────────

    public function show($id)
    {
        $notulensi = Notulensi::with([
            'fakultas',
            'user',
            'dosens',
            'dosens.prodi',
            'dosens.prodi.fakultas',
            'dokumentasiNotulensi',
        ])->findOrFail($id);

        return response()->json($notulensi);
    }

    // ── UPDATE ──────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'judul'         => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'tempat'        => 'required|string|max:255',
            'agenda'        => 'required|string',
            'tindak_lanjut' => 'nullable|string',
            'peserta'       => 'required|array|min:1',
            'peserta.*'     => 'exists:dosens,id',
            'fakultas_id'   => 'nullable|exists:fakultas,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $notulensi  = Notulensi::findOrFail($id);
        $fakultasId = ($user->role === 'super_admin' && $request->filled('fakultas_id'))
            ? $request->fakultas_id
            : $notulensi->fakultas_id;

        $notulensi->update([
            'judul'         => $request->judul,
            'tanggal'       => $request->tanggal,
            'tempat'        => $request->tempat,
            'agenda'        => $request->agenda,
            'tindak_lanjut' => $request->tindak_lanjut ?? null,
            'fakultas_id'   => $fakultasId,
        ]);

        // Sync peserta
        PesertaRapat::where('notulensi_id', $id)->delete();
        foreach ($request->peserta as $dosenId) {
            PesertaRapat::create(['notulensi_id' => $id, 'dosen_id' => $dosenId]);
        }

        if ($request->has('deleted_dokumentasi') && is_array($request->deleted_dokumentasi)) {
            foreach ($request->deleted_dokumentasi as $dokId) {
                $dok = DokumentasiNotulensi::where('id', $dokId)
                            ->where('notulensi_id', $id)
                            ->first();
                if ($dok) {
                    Storage::disk('public')->delete($dok->path_file);
                    $dok->delete();
                }
            }
        }

        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('dokumentasi-notulensi', $namaFile, 'public');
                DokumentasiNotulensi::create([
                    'notulensi_id' => $notulensi->id,
                    'nama_file'    => $namaFile,
                    'path_file'    => $path,
                ]);
            }
        }

        // ── Notifikasi ───────────────────────────────────────
        $notifUrl = route('notulensi.index');

        NotifikasiHelper::notifNotulensi(
            'Notulensi Diperbarui',
            'Notulensi "' . $notulensi->judul . '" telah diperbarui.',
            $notifUrl
        );

        NotifikasiHelper::notifDosenPeserta(
            $request->peserta,
            'Data Rapat Diperbarui',
            'Data rapat "' . $notulensi->judul . '" yang Anda ikuti telah diperbarui.',
            $notifUrl
        );

        return response()->json([
            'success' => true,
            'message' => 'Notulensi berhasil diperbarui.',
        ]);
    }

    // ── DESTROY ─────────────────────────────────────────

    public function destroy($id)
    {
        Notulensi::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notulensi berhasil dihapus.',
        ]);
    }

    // ── EXPORT BAP ──────────────────────────────────────

    public function exportBap($id, Request $request)
    {
        $notulensi = Notulensi::with([
            'fakultas',
            'user',
            'dosens',
            'dosens.prodi',
            'dosens.prodi.fakultas',
        ])->findOrFail($id);

        $showTtd     = (bool) $request->get('show_ttd', false);
        $namaDekan   = null;
        $namaKaprodi = null;

        if ($showTtd) {
            $namaDekan   = $notulensi->fakultas->nama_dekan ?? null;
            $namaKaprodi = $notulensi->dosens->first()?->prodi?->nama_kaprodi ?? null;
        }

        $pdf = Pdf::loadView('notulensi.bap', compact(
            'notulensi', 'showTtd', 'namaDekan', 'namaKaprodi'
        ))->setPaper('a4', 'portrait');

        $filename = 'BAP-' . $notulensi->nomor_bap . '.pdf';
        $filename = str_replace('/', '-', $filename);

        return $pdf->download($filename);
    }

    // ── GET BY DOSEN ─────────────────────────────────────

    public function getByDosen($dosenId)
    {
        $notulensis = Notulensi::with(['fakultas', 'user'])
            ->whereHas('pesertaRapat', fn($q) => $q->where('dosen_id', $dosenId))
            ->latest()
            ->get();

        return response()->json($notulensis);
    }
}
