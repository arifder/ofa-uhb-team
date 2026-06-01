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

    // ─────────────────────────────────────────────────────────────────
    // NOTULENSI NOTIFICATIONS
    // ─────────────────────────────────────────────────────────────────

    /**
     * Notif rapat dibuat → admin notulensi, super_admin, kepala_unit.
     */
manajemen-kas-oza
    public static function notifNotulensi(
        string $judul,
        string $pesan,
        ?string $url = null
    ): void {
        self::kirimKeRole([
            'super_admin',
            'admin_fst',
            'admin_fis',
            'kepala_unit',
        ], $judul, $pesan, 'notulensi', $url);
  
    public static function notifNotulensiDibuat(string $judul, ?string $url = null): void
    {
        self::kirimKeRole(
            ['super_admin', 'admin_notulensi_fst', 'admin_notulensi_fis', 'kepala_unit'],
            '📋 Notulensi Baru Dibuat',
            "Notulensi rapat \"$judul\" telah ditambahkan.",
            'notulensi',
            $url
        );
    }

    /**
     * Notif rapat diedit → admin notulensi, super_admin, kepala_unit.
     */
    public static function notifNotulensiDiedit(string $judul, ?string $url = null): void
    {
        self::kirimKeRole(
            ['super_admin', 'admin_notulensi_fst', 'admin_notulensi_fis', 'kepala_unit'],
            '✏️ Notulensi Diperbarui',
            "Notulensi rapat \"$judul\" telah diperbarui.",
            'notulensi',
            $url
        );
    }

    /**
     * Notif rapat dihapus → admin notulensi, super_admin, kepala_unit.
     */
    public static function notifNotulensiDihapus(string $judul, ?string $url = null): void
    {
        self::kirimKeRole(
            ['super_admin', 'admin_notulensi_fst', 'admin_notulensi_fis', 'kepala_unit'],
            '🗑️ Notulensi Dihapus',
            "Notulensi rapat \"$judul\" telah dihapus dari sistem.",
            'notulensi',
            $url
        );
  main
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

    // ─────────────────────────────────────────────────────────────────
    // KAS NOTIFICATIONS
    // ─────────────────────────────────────────────────────────────────

    /**
     * Notif kas masuk → admin kas, super_admin, kepala_unit.
     */
    public static function notifKasMasuk(
        string $keterangan,
        string $jumlahFormatted,
        ?string $url = null
    ): void {
        self::kirimKeRole(
            ['super_admin', 'admin_kas_fst', 'admin_kas_fis', 'kepala_unit'],
            '💰 Kas Masuk Baru',
            "Kas masuk sebesar Rp $jumlahFormatted telah dicatat. ($keterangan)",
            'kas',
            $url
        );
    }

    /**
     * Notif kas keluar → admin kas, super_admin, kepala_unit.
     */
    public static function notifKasKeluar(
        string $keterangan,
        string $jumlahFormatted,
        ?string $url = null
    ): void {
        self::kirimKeRole(
            ['super_admin', 'admin_kas_fst', 'admin_kas_fis', 'kepala_unit'],
            '📤 Kas Keluar Dicatat',
            "Kas keluar sebesar Rp $jumlahFormatted telah dicatat. ($keterangan)",
            'kas',
            $url
        );
    }

    /**
     * Notif penagihan ke dosen + admin + super_admin + kepala_unit.
     */
    public static function notifTagihanDibuat(
        int $dosenUserId,
        string $namaDosen,
        string $bulan,
        int $tahun,
        string $jumlahFormatted,
        ?string $urlDosen = null,
        ?string $urlAdmin = null
    ): void {
        // Notif ke dosen ybs
        self::kirim(
            $dosenUserId,
            '🧾 Tagihan Kas Baru',
            "Tagihan kas bulan $bulan $tahun sebesar Rp $jumlahFormatted telah diterbitkan. Segera lakukan pembayaran.",
            'kas',
            $urlDosen
        );

        // Notif ke admin kas & manajemen
        self::kirimKeRole(
            ['super_admin', 'admin_kas_fst', 'admin_kas_fis', 'kepala_unit'],
            '🧾 Tagihan Dosen Diterbitkan',
            "Tagihan kas bulan $bulan $tahun sebesar Rp $jumlahFormatted telah diterbitkan untuk $namaDosen.",
            'kas',
            $urlAdmin
        );
    }

    /**
     * Notif pembayaran tagihan oleh dosen → dosen ybs + admin + super_admin + kepala_unit.
     */
    public static function notifPembayaranTagihan(
        int $dosenUserId,
        string $namaDosen,
        string $bulan,
        int $tahun,
        string $jumlahFormatted,
        ?string $urlDosen = null,
        ?string $urlAdmin = null
    ): void {
        // Notif konfirmasi ke dosen
        self::kirim(
            $dosenUserId,
            '✅ Pembayaran Tagihan Berhasil',
            "Pembayaran tagihan kas bulan $bulan $tahun sebesar Rp $jumlahFormatted berhasil dikonfirmasi.",
            'kas',
            $urlDosen
        );

        // Notif ke admin kas & manajemen
        self::kirimKeRole(
            ['super_admin', 'admin_kas_fst', 'admin_kas_fis', 'kepala_unit'],
            '✅ Tagihan Dibayar',
            "$namaDosen telah membayar tagihan bulan $bulan $tahun sebesar Rp $jumlahFormatted.",
            'kas',
            $urlAdmin
        );
    }

    /**
     * @deprecated Gunakan notifNotulensiDibuat/Diedit/Dihapus secara langsung.
     */
    public static function notifNotulensi(
        string $judul,
        string $pesan,
        ?string $url = null
    ): void {
        self::kirimKeRole(
            ['super_admin', 'admin_notulensi_fst', 'admin_notulensi_fis', 'kepala_unit'],
            $judul, $pesan, 'notulensi', $url
        );
    }
}
