<?php

namespace App\Helpers;

use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Dosen;

class NotifikasiHelper
{
    /**
     * Kirim notifikasi ke satu user.
     */
    public static function kirim(
        int $userId,
        string $judul,
        string $pesan,
        string $tipe = 'sistem',
        ?string $url = null
    ): void {
        Notifikasi::create([
            'user_id' => $userId,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'tipe'    => $tipe,
            'url'     => $url,
            'dibaca'  => false,
        ]);
    }

    /**
     * Kirim notifikasi ke semua user dengan role tertentu.
     */
    public static function kirimKeRole(
        array|string $roles,
        string $judul,
        string $pesan,
        string $tipe = 'sistem',
        ?string $url = null
    ): void {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $users = User::whereIn('role', $roles)->get();

        foreach ($users as $user) {
            self::kirim($user->id, $judul, $pesan, $tipe, $url);
        }
    }

    /**
     * Kirim notifikasi terkait notulensi ke admin & kepala unit.
     */
    public static function notifNotulensi(
        string $judul,
        string $pesan,
        ?string $url = null
    ): void {
        self::kirimKeRole([
            'super_admin',
            'admin_notulensi_fst',
            'admin_notulensi_fis',
            'kepala_unit',
        ], $judul, $pesan, 'notulensi', $url);
    }

    /**
     * Kirim notifikasi ke dosen-dosen peserta rapat (berdasarkan dosen ID).
     */
    public static function notifDosenPeserta(
        array $dosenIds,
        string $judul,
        string $pesan,
        ?string $url = null
    ): void {
        $userIds = Dosen::whereIn('id', $dosenIds)->pluck('user_id');
        $users   = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            self::kirim($user->id, $judul, $pesan, 'notulensi', $url);
        }
    }
}
