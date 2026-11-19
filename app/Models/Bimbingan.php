<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;
    protected $guarded = []; // Biar gampang mass assignment

    public function details()
    {
        return $this->hasMany(BimbinganDetail::class);
    }

    protected $fillable = [
        'nama_mahasiswa',
        'nim',
        'semester',
        'tahun_akademik',
        'nama_dosen',
        'nip_dosen',
        'jenis_bimbingan',
        'jumlah_bimbingan',
        'catatan_umum',
        'kegiatan_pengesahan',
        'file_pdf',
        'status' // <-- Tambahkan ini
    ];
}
