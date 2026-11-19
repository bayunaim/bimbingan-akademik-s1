<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama']) || !isset($row['email'])) {
            return null;
        }

        // Cari ID Dosen berdasarkan NIP yang ada di Excel untuk Auto-Plotting
        $dosen_id = null;
        if (isset($row['nip_dosen']) && $row['nip_dosen'] != '') {
            $dosen = User::where('role', 'dosen')->where('nomor_induk', $row['nip_dosen'])->first();
            if ($dosen) {
                $dosen_id = $dosen->id;
            }
        }

        return new User([
            'name'        => $row['nama'],
            'nomor_induk' => $row['nim'] ?? null,
            'email'       => $row['email'],
            'prodi'       => $row['prodi'] ?? null, // 🟢 Tangkap prodi dari excel
            'dosen_id'    => $dosen_id,
            'password'    => Hash::make('password123'),
            'role'        => 'mahasiswa',
        ]);
    }
}
