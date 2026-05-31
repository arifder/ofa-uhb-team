<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'url',
        'dibaca',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    // ── Relationships ───────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ─────────────────────────────────────────

    public function scopeBelumDibaca($q)
    {
        return $q->where('dibaca', false);
    }
}
