<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    // Database memiliki 'nama_prodi' bukan 'nama'
    protected $fillable = ['nama_prodi', 'nama_kaprodi', 'fakultas_id'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function dosens()
    {
        return $this->hasMany(Dosen::class);
    }
}
