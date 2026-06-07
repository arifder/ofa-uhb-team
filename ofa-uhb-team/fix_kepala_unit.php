<?php

// Cari user dengan role kepala_unit
$user = \App\Models\User::where('role', 'kepala_unit')->first();

if ($user) {
    $user->password = bcrypt('kepalunit123');
    $user->save();
    echo "✅ User ditemukan:\n";
    echo "   Nama  : {$user->name}\n";
    echo "   Email : {$user->email}\n";
    echo "   Role  : {$user->role}\n";
    echo "   Password baru: kepalunit123\n";
} else {
    echo "❌ Tidak ada user dengan role 'kepala_unit'.\n";
    echo "   Semua user yang ada:\n";
    \App\Models\User::select('id', 'name', 'email', 'role')->get()->each(function ($u) {
        echo "   - [{$u->role}] {$u->name} <{$u->email}>\n";
    });
}
