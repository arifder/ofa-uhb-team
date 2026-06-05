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
        $rules = [
            'name' => 'required',
            'role' => 'required|in:super_admin,admin_fst,admin_fis,kepala_unit,dosen',
            'status' => 'required|in:aktif,arsip',
            'jabatan_struktural' => 'nullable|string|in:Super Admin,Dekan,Kaprodi,BAAK,Kemahasiswaan,LPPM,Dosen,Kepala Unit,Lainnya',
        ];

        if ($request->role === 'dosen') {
            $rules['username'] = 'required|unique:users,username|unique:dosens,nidn';
            $rules['email'] = 'nullable|email|unique:users,email';
            $rules['password'] = 'nullable|min:6';
            $rules['prodi_id'] = 'required|exists:prodis,id';
        } else {
            $rules['username'] = 'required|unique:users,username';
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6';
            $rules['fakultas_id'] = 'nullable';
        }

        $request->validate($rules);

        $fakultasId = $request->fakultas_id ?: null;
        $prodiId = $request->prodi_id ?: null;

        if ($request->role === 'dosen') {
            $prodi = \App\Models\Prodi::findOrFail($request->prodi_id);
            $fakultasId = $prodi->fakultas_id;

            if (!$request->filled('email')) {
                $namaSlug = strtolower(str_replace([' ', '.'], '', $request->name));
                $email = $namaSlug . '@ofa.com';
            } else {
                $email = $request->email;
            }

            if (!$request->filled('password')) {
                $namaSlug = strtolower(str_replace([' ', '.'], '', $request->name));
                $password = $namaSlug . '123';
            } else {
                $password = $request->password;
            }
        } else {
            $email = $request->email;
            $password = $request->password;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $email, $password, $fakultasId, $prodiId) {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $request->role,
                'fakultas_id' => $fakultasId,
                'prodi_id' => $prodiId,
                'status' => $request->status,
                'jabatan_struktural' => $request->jabatan_struktural,
            ]);

            if ($request->role === 'dosen') {
                \App\Models\Dosen::create([
                    'user_id' => $user->id,
                    'prodi_id' => $prodiId,
                    'nidn' => $request->username,
                    'nama_lengkap' => $request->name,
                    'status' => $request->status === 'aktif' ? 'aktif' : 'nonaktif',
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required',
            'role' => 'required|in:super_admin,admin_fst,admin_fis,kepala_unit,dosen',
            'status' => 'required|in:aktif,arsip',
            'jabatan_struktural' => 'nullable|string|in:Super Admin,Dekan,Kaprodi,BAAK,Kemahasiswaan,LPPM,Dosen,Kepala Unit,Lainnya',
        ];

        if ($request->role === 'dosen') {
            $dosen = $user->dosen;
            $dosenIdRule = $dosen ? ',' . $dosen->id : '';
            $rules['username'] = 'required|unique:users,username,' . $user->id . '|unique:dosens,nidn' . $dosenIdRule;
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
            $rules['password'] = 'nullable|min:6';
            $rules['prodi_id'] = 'required|exists:prodis,id';
        } else {
            $rules['username'] = 'required|unique:users,username,' . $user->id;
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
            $rules['password'] = 'nullable|min:6';
            $rules['fakultas_id'] = 'nullable';
        }

        $request->validate($rules);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->role === 'dosen') {
            $prodi = \App\Models\Prodi::findOrFail($request->prodi_id);
            $data['fakultas_id'] = $prodi->fakultas_id;
            $data['prodi_id'] = $request->prodi_id;
        } else {
            $data['fakultas_id'] = $request->fakultas_id ?: null;
            $data['prodi_id'] = null;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $user, $data) {
            $user->update($data);

            if ($request->role === 'dosen') {
                \App\Models\Dosen::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'prodi_id' => $request->prodi_id,
                        'nidn' => $request->username,
                        'nama_lengkap' => $request->name,
                        'status' => $request->status === 'aktif' ? 'aktif' : 'nonaktif',
                    ]
                );
            } else {
                \App\Models\Dosen::where('user_id', $user->id)->delete();
            }
        });

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
