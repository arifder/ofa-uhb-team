<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['fakultas', 'prodi'])->where('status', 'aktif');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        $fakultasList = \App\Models\Fakultas::all();
        return view('master.users.index', compact('users', 'fakultasList'));
    }

    public function arsipIndex(Request $request)
    {
        $query = User::with(['fakultas', 'prodi'])->where('status', 'arsip');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        return view('master.users.arsip', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => $request->role === 'dosen' ? 'nullable|email|unique:users' : 'required|email|unique:users',
            'password' => $request->role === 'dosen' ? 'nullable|min:6' : 'required|min:6',
            'role' => 'required|in:super_admin,admin_fakultas,admin_notulensi_fst,admin_notulensi_fis,admin_kas_fst,admin_kas_fis,kepala_unit,dosen',
            'fakultas_id' => 'nullable',
            'prodi_id' => 'nullable',
            'status' => 'required|in:aktif,arsip',
        ]);

        if ($request->role === 'dosen' && !$request->filled('email')) {
            $namaSlug = strtolower(str_replace([' ', '.'], '', $request->name));
            $email = $namaSlug . '@ofa.com';
            $password = $namaSlug . '123';
        } else {
            $email = $request->email;
            $password = $request->password ? $request->password : $request->username . '123';
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'fakultas_id' => $request->fakultas_id ?: null,
            'prodi_id' => $request->prodi_id ?: null,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:super_admin,admin_fakultas,admin_notulensi_fst,admin_notulensi_fis,admin_kas_fst,admin_kas_fis,kepala_unit,dosen',
            'fakultas_id' => 'nullable',
            'prodi_id' => 'nullable',
            'status' => 'required|in:aktif,arsip',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['success' => true, 'message' => 'User berhasil diupdate']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'aktif' ? 'arsip' : 'aktif';
        $user->save();

        return response()->json(['success' => true, 'message' => 'Status user berhasil diubah']);
    }
}
