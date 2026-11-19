<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Pastikan baris tidak kosong
        if (!isset($row['nama']) || !isset($row['email'])) {
            return null;
        }

        return new User([
            'name'        => $row['nama'],
            'nomor_induk' => $row['nip'],
            'email'       => $row['email'],
            'prodi'       => $row['prodi'] ?? null,
            'password'    => Hash::make('password123'), // Password default
            'role'        => 'dosen',
        ]);
    }
}
