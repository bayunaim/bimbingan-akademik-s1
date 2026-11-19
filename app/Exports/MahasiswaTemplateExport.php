<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MahasiswaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'nama',
            'nim',
            'email',
            'prodi',
            'nip_dosen' // Kolom sakti buat Auto-Plotting
        ];
    }

    public function array(): array
    {
        return [
            ['Andi Pratama', '211031005', 'andi@binaguna.ac.id', 'IKOR', '198001012005011001']
        ];
    }
}
