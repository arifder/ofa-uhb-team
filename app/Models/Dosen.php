<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = ['user_id', 'prodi_id', 'nidn', 'nama_lengkap', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function getFakultasAttribute()
    {
        return $this->prodi ? $this->prodi->fakultas : null;
    }

    public function notulensis()
    {
        return $this->belongsToMany(
            Notulensi::class,
            'peserta_rapat',
            'dosen_id',
            'notulensi_id'
        );
    }
}
