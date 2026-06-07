<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaRapat extends Model
{
    protected $table = 'peserta_rapat';

    protected $fillable = [
        'notulensi_id',
        'dosen_id',
    ];

    public function notulensi()
    {
        return $this->belongsTo(Notulensi::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'id');
    }
}
