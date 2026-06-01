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
     * kepala_unit, admin_notulensi_fst, admin_notulensi_fis boleh melihat daftar.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [
            'kepala_unit',
            'admin_notulensi_fst',
            'admin_notulensi_fis',
        ]);
    }

    /**
     * kepala_unit, admin_notulensi_fst, admin_notulensi_fis boleh melihat detail.
     */
    public function view(User $user, Notulensi $notulensi): bool
    {
        return in_array($user->role, [
            'kepala_unit',
            'admin_notulensi_fst',
            'admin_notulensi_fis',
        ]);
    }

    /**
     * Hanya admin notulensi yang boleh membuat — kepala_unit TIDAK boleh.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [
            'admin_notulensi_fst',
            'admin_notulensi_fis',
        ]);
    }

    /**
     * Hanya admin notulensi yang boleh mengubah — kepala_unit TIDAK boleh.
     */
    public function update(User $user, Notulensi $notulensi): bool
    {
        return in_array($user->role, [
            'admin_notulensi_fst',
            'admin_notulensi_fis',
        ]);
    }

    /**
     * Hanya admin notulensi yang boleh menghapus — kepala_unit TIDAK boleh.
     */
    public function delete(User $user, Notulensi $notulensi): bool
    {
        return in_array($user->role, [
            'admin_notulensi_fst',
            'admin_notulensi_fis',
        ]);
    }
}
