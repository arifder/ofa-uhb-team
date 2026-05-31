<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required',
            'fakultas_id' => 'required|exists:fakultas,id'
        ]);

        Prodi::create($request->only(['nama_prodi', 'fakultas_id']));

        return response()->json(['success' => true, 'message' => 'Prodi berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_prodi' => 'required'
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update($request->only(['nama_prodi', 'fakultas_id']));

        return response()->json(['success' => true, 'message' => 'Prodi berhasil diupdate']);
    }

    public function destroy($id)
    {
        Prodi::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Prodi berhasil dihapus']);
    }

    public function getByFakultas($id)
    {
        $prodis = Prodi::where('fakultas_id', $id)->get();
        return response()->json($prodis);
    }
}
