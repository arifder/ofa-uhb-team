<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index()
    {
        $fakultas = Fakultas::with('prodis')->withCount('dosens')->get();
        return view('master.fakultas.index', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fakultas' => 'required|unique:fakultas,nama_fakultas'
        ]);

        Fakultas::create($request->all());

        return response()->json(['success' => true, 'message' => 'Fakultas berhasil ditambahkan!']);
    }

    public function update(Request $request, $id)
    {
        $fakulta = Fakultas::findOrFail($id);
        
        $request->validate([
            'nama_fakultas' => 'required|unique:fakultas,nama_fakultas,' . $fakulta->id
        ]);

        $fakulta->update($request->all());

        return response()->json(['success' => true, 'message' => 'Fakultas berhasil diupdate!']);
    }

    public function destroy($id)
    {
        Fakultas::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Fakultas berhasil dihapus!']);
    }
}