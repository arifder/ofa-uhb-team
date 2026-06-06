<?php

namespace App\Policies;

use App\Models\KasTagihan;
use App\Models\User;

class KasTagihanPolicy
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

    public function view(User $user, KasTagihan $tagihan): bool
    {
        if ($user->hasRole('dosen')) {
            return $tagihan->dosen_id == $user->dosen?->id;
        }
        return $user->hasRole(['admin_kas_fst', 'admin_kas_fis', 'kepala_unit']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin_kas_fst', 'admin_kas_fis']);
    }

    public function update(User $user, KasTagihan $tagihan): bool
    {
        if ($user->hasRole(['admin_kas_fst', 'admin_kas_fis'])) {
            return $tagihan->fakultas_id == $user->fakultas_id;
        }
        return false;
    }

    public function delete(User $user, KasTagihan $tagihan): bool
    {
        if ($user->hasRole(['admin_kas_fst', 'admin_kas_fis'])) {
            return $tagihan->fakultas_id == $user->fakultas_id;
        }
        return false;
    }

    public function pay(User $user, KasTagihan $tagihan): bool
    {
        if ($user->hasRole('dosen')) {
            $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
            return $tagihan->dosen_id == $dosen?->id;
        }
        if ($user->hasRole(['admin_kas_fst', 'admin_kas_fis'])) {
            return $tagihan->fakultas_id == $user->fakultas_id;
        }
        return false;
    }
}
