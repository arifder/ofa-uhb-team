<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentasiNotulensi extends Model
{
    protected $table = 'dokumentasi_notulensi';

    protected $fillable = [
        'notulensi_id',
        'nama_file',
        'path_file',
    ];

    public function notulensi()
    {
        return $this->belongsTo(Notulensi::class);
    }
}
