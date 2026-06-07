<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class KasTagihan extends Model
{
    protected $fillable = [
        'dosen_id',
        'fakultas_id',
        'bulan',
        'tahun',
        'jumlah',
        'tanggal_jatuh_tempo',
        'status',
        'dibayar_amount',
        'dibayar_tanggal',
        'user_id',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'dibayar_amount' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'dibayar_tanggal' => 'date',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(KasTransaction::class, 'tagihan');
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status', 'belum_lunas');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeByPeriode($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }
}