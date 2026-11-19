<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin pusat (melihat semua prodi)
        User::create([
            'name' => 'Admin Pusat',
            'email' => 'admin@binaguna.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Satu akun kaprodi + dosen + mahasiswa contoh per prodi
        foreach (Prodi::all() as $prodi) {
            $kode = strtolower($prodi->kode);

            User::create([
                'name' => 'Kaprodi '.$prodi->kode,
                'email' => "kaprodi.$kode@binaguna.ac.id",
                'password' => Hash::make('password123'),
                'role' => 'kaprodi',
                'prodi_id' => $prodi->id,
            ]);

            $dosen = User::create([
                'name' => 'Dosen '.$prodi->kode,
                'email' => "dosen.$kode@binaguna.ac.id",
                'nomor_induk' => '19800101200501'.str_pad($prodi->id, 4, '0', STR_PAD_LEFT),
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'prodi_id' => $prodi->id,
            ]);

            User::create([
                'name' => 'Mahasiswa '.$prodi->kode,
                'email' => "mhs.$kode@binaguna.ac.id",
                'nomor_induk' => '2610'.str_pad($prodi->id, 5, '0', STR_PAD_LEFT),
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'prodi_id' => $prodi->id,
                'dosen_id' => $dosen->id,
            ]);
        }
    }
}
