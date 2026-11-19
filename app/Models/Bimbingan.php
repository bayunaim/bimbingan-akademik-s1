<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Bimbingan extends Model
{
    use HasFactory;
    protected $guarded = []; // Biar gampang mass assignment

    public function mahasiswa() { return $this->belongsTo(User::class, 'mahasiswa_id'); }
    public function dosen()     { return $this->belongsTo(User::class, 'dosen_id'); }
    public function prodi()     { return $this->belongsTo(Prodi::class); }

    /**
     * Batasi data sesuai siapa yang login:
     * mahasiswa = miliknya, dosen = bimbingannya, kaprodi = prodinya, admin = semua.
     */
    public function scopeVisibleTo($query, User $user)
    {
        return match ($user->role) {
            'mahasiswa' => $query->where('mahasiswa_id', $user->id),
            'dosen'     => $query->where('dosen_id', $user->id),
            'kaprodi'   => $query->where('prodi_id', $user->prodi_id),
            'admin'     => $query,
            default     => $query->whereRaw('1 = 0'),
        };
    }

    public function details()
    {
        return $this->hasMany(BimbinganDetail::class);
    }

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'prodi_id',
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
