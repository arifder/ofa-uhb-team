<?php

namespace App\Policies;

use App\Models\Notulensi;
use App\Models\User;

class NotulensiPolicy
{
    /**
     * super_admin mendapat semua akses secara otomatis.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return null;
    }

    /**
     * kepala_unit, admin_fst, admin_fis, dosen boleh melihat daftar.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [
            'kepala_unit',
            'admin_fst',
            'admin_fis',
            'dosen',
        ]);
    }

    /**
     * kepala_unit, admin_fst, admin_fis, dosen boleh melihat detail.
     */
    public function view(User $user, Notulensi $notulensi): bool
    {
        return in_array($user->role, [
            'kepala_unit',
            'admin_fst',
            'admin_fis',
            'dosen',
        ]);
    }

    /**
     * Hanya admin fst/fis yang boleh membuat — kepala_unit TIDAK boleh.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [
            'admin_fst',
            'admin_fis',
        ]);
    }

    /**
     * Hanya admin fst/fis yang boleh mengubah — kepala_unit TIDAK boleh.
     */
    public function update(User $user, Notulensi $notulensi): bool
    {
        if (in_array($user->role, ['admin_fst', 'admin_fis'])) {
            return (int) $notulensi->fakultas_id === (int) $user->fakultas_id;
        }
        return false;
    }

    /**
     * Hanya admin fst/fis yang boleh menghapus — kepala_unit TIDAK boleh.
     */
    public function delete(User $user, Notulensi $notulensi): bool
    {
        if (in_array($user->role, ['admin_fst', 'admin_fis'])) {
            return (int) $notulensi->fakultas_id === (int) $user->fakultas_id;
        }
        return false;
    }
}
