<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notulensi extends Model
{
    protected $table = 'notulensi';

    protected $fillable = [
        'judul',
        'tanggal',
        'tempat',
        'agenda',
        'tindak_lanjut',
        'fakultas_id',
        'user_id',
        'nomor_bap',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // ── Relationships ───────────────────────────────────

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pesertaRapat()
    {
        return $this->hasMany(PesertaRapat::class);
    }

    public function dokumentasiNotulensi()
    {
        return $this->hasMany(DokumentasiNotulensi::class);
    }

    public function dosens()
    {
        return $this->belongsToMany(
            Dosen::class,
            'peserta_rapat',
            'notulensi_id',
            'dosen_id'
        );
    }

    // ── Scopes ─────────────────────────────────────────

    public function scopeFakultas($query, $fakultasId)
    {
        return $query->where('fakultas_id', $fakultasId);
    }

    // ── Static Helpers ──────────────────────────────────

    /**
     * Generate nomor BAP otomatis.
     * Format: BAP/[KODE]/[ROMAWI]/[TAHUN]/[NOMOR]
     * Contoh: BAP/FST/V/2026/001
     */
    public static function generateNomorBap(int $fakultasId): string
    {
        $fakultas = Fakultas::find($fakultasId);
        $namaFak  = $fakultas ? $fakultas->nama_fakultas : '';

        if (stripos($namaFak, 'Sains') !== false) {
            $kode = 'FST';
        } elseif (stripos($namaFak, 'Sosial') !== false) {
            $kode = 'FIS';
        } else {
            $kode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $namaFak), 0, 3));
        }

        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        $bulan  = $romawi[now()->month];
        $tahun  = now()->year;
        $urutan = self::count() + 1;
        $nomor  = str_pad($urutan, 3, '0', STR_PAD_LEFT);

        return "BAP/{$kode}/{$bulan}/{$tahun}/{$nomor}";
    }
}
