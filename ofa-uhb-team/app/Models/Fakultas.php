<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    // Cukup satu baris ini saja
    protected $fillable = ['nama_fakultas', 'nama_dekan'];

    public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }

    public function dosens()
    {
        return $this->hasManyThrough(Dosen::class, Prodi::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
