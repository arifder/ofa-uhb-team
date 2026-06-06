<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::with(['user', 'prodi.fakultas']);

        if ($request->filled('fakultas_id')) {
            $query->whereHas('prodi', function($q) use ($request) {
                $q->where('fakultas_id', $request->fakultas_id);
            });
        }

        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%");
            });
        }

        $dosens = $query->paginate(10);
        $fakultasList = \App\Models\Fakultas::all();
        return view('master.dosen.index', compact('dosens', 'fakultasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'nidn' => 'required|unique:dosens,nidn|digits:10',
            'prodi_id' => 'required|exists:prodis,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $prodi = Prodi::findOrFail($request->prodi_id);

        DB::transaction(function () use ($request, $prodi) {
            $user = User::create([
                'name' => $request->nama_lengkap,
                'username' => $request->nidn, 
                'email' => $request->nidn . '@ofa-uhb.com',
                'password' => Hash::make($request->nidn),
                'role' => 'dosen',
                'fakultas_id' => $prodi->fakultas_id,
                'prodi_id' => $request->prodi_id,
                'status' => $request->status,
            ]);

            Dosen::create([
                'user_id' => $user->id,
                'prodi_id' => $request->prodi_id,
                'nidn' => $request->nidn,
                'nama_lengkap' => $request->nama_lengkap,
                'status' => $request->status,
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Dosen berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nama_lengkap'    => 'required',
            'nidn'            => 'required|unique:dosens,nidn,' . $dosen->id . '|digits:10',
            'prodi_id'        => 'required|exists:prodis,id',
            'status'          => 'required|in:aktif,nonaktif',
            'nominal_tagihan' => 'nullable|integer|min:0',
        ]);

        $prodi = Prodi::findOrFail($request->prodi_id);

        DB::transaction(function () use ($request, $dosen, $prodi) {
            $dosen->update([
                'prodi_id'        => $request->prodi_id,
                'nidn'            => $request->nidn,
                'nama_lengkap'    => $request->nama_lengkap,
                'status'          => $request->status,
                'nominal_tagihan' => $request->input('nominal_tagihan', 0),
            ]);

            if ($dosen->user) {
                $dosen->user->update([
                    'name' => $request->nama_lengkap,
                    'fakultas_id' => $prodi->fakultas_id,
                    'prodi_id' => $request->prodi_id,
                    'status' => $request->status,
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Dosen berhasil diupdate']);
    }

    public function updateNominal(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nominal_tagihan' => 'required|integer|min:0',
        ]);

        $dosen->update([
            'nominal_tagihan' => $request->nominal_tagihan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nominal tagihan berhasil disimpan',
            'nominal' => $dosen->nominal_tagihan,
        ]);
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        
        DB::transaction(function () use ($dosen) {
            $user = $dosen->user;
            $dosen->delete();
            if ($user) {
                $user->delete();
            }
        });
        
        return response()->json(['success' => true, 'message' => 'Dosen berhasil dihapus']);
    }
}
