<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class KasTransaction extends Model
{
    protected $fillable = [
        'jenis',
        'jumlah',
        'tanggal',
        'keterangan',
        'fakultas_id',
        'user_id',
        'referensi_id',
        'referensi_type',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date',
    ];

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tagihan(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeMasuk($query)
    {
        return $query->where('jenis', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('jenis', 'keluar');
    }

    public function scopeFakultas($query, $fakultasId)
    {
        return $query->where('fakultas_id', $fakultasId);
    }
}