<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Bikin Akun Admin (Kaprodi)
        User::create([
            'name' => 'Kaprodi Admin',
            'email' => 'admin@binaguna.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Bikin Akun Dosen
        User::create([
            'name' => 'Dr. Dosen Pembimbing',
            'email' => 'dosen@binaguna.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
        ]);

        // Bikin Akun Mahasiswa
        User::create([
            'name' => 'Mahasiswa STOK',
            'email' => 'mahasiswa@binaguna.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);
    }
}
