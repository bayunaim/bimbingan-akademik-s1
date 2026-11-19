<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DosenTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        // Tambahkan kolom 'prodi'
        return [
            'nama',
            'nip',
            'email',
            'prodi'
        ];
    }

    public function array(): array
    {
        // Kolom prodi diisi KODE prodi: IKOR / PJKR / PKH
        return [
            ['Dr. Budi Santoso, M.Or', '198001012005011001', 'budi@binaguna.ac.id', 'IKOR']
        ];
    }
}
