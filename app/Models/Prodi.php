<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = ['kode', 'nama', 'nama_kaprodi', 'nip_kaprodi'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class);
    }
}
