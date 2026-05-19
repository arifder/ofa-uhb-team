<?php

namespace App\Policies;

use App\Models\KasTransaction;
use App\Models\User;

class KasTransactionPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super_admin')) return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin_kas_fst', 'admin_kas_fis', 'kepala_unit', 'dosen']);
    }

    public function view(User $user, KasTransaction $kas): bool
    {
        return $user->hasRole(['admin_kas_fst', 'admin_kas_fis', 'kepala_unit', 'dosen']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin_kas_fst', 'admin_kas_fis']);
        // kepala_unit & dosen TIDAK bisa
    }

    public function update(User $user, KasTransaction $kas): bool
    {
        if ($user->hasRole(['admin_kas_fst', 'admin_kas_fis'])) {
            return $kas->fakultas_id == $user->fakultas_id;
        }
        return false;
    }

    public function delete(User $user, KasTransaction $kas): bool
    {
        if ($user->hasRole(['admin_kas_fst', 'admin_kas_fis'])) {
            return $kas->fakultas_id == $user->fakultas_id;
        }
        return false;
    }
}
