<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        // TODO: sesuaikan nama resmi prodi, nama & NIP kaprodi
        $data = [
            ['kode' => 'IKOR', 'nama' => 'Ilmu Keolahragaan'],
            ['kode' => 'PJKR', 'nama' => 'Pendidikan Jasmani, Kesehatan dan Rekreasi'],
            ['kode' => 'PKH',  'nama' => 'Pendidikan Kepelatihan Olahraga'],
        ];

        foreach ($data as $row) {
            Prodi::updateOrCreate(['kode' => $row['kode']], $row);
        }
    }
}
