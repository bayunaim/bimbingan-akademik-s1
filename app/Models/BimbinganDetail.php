<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bimbingan()
    {
        return $this->belongsTo(Bimbingan::class);
    }
}
