<?php

namespace App\Imports;

use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToModel, WithHeadingRow
{
    private int $berhasil = 0;
    private array $dilewati = [];

    public function __construct(private User $importer) {}

    public function model(array $row)
    {
        if (empty($row['nama']) || empty($row['email'])) {
            return null;
        }

        // Kaprodi: dipaksa ke prodinya sendiri. Admin: baca kolom "prodi" (kode).
        if ($this->importer->isKaprodi()) {
            $prodiId = $this->importer->prodi_id;
        } else {
            $prodiId = Prodi::where('kode', strtoupper(trim($row['prodi'] ?? '')))->value('id');
            if (!$prodiId) {
                $this->dilewati[] = "{$row['email']} (kode prodi tidak dikenal)";
                return null;
            }
        }

        if (User::where('email', $row['email'])->exists()) {
            $this->dilewati[] = "{$row['email']} (email sudah terdaftar)";
            return null;
        }

        $this->berhasil++;

        return new User([
            'name'        => $row['nama'],
            'nomor_induk' => (string) ($row['nip'] ?? ''),
            'email'       => $row['email'],
            'prodi_id'    => $prodiId,
            'password'    => Hash::make('password123'),
            'role'        => 'dosen',
        ]);
    }

    public function summary(): string
    {
        $msg = "{$this->berhasil} dosen berhasil di-import.";
        if ($this->dilewati) {
            $msg .= ' Dilewati: ' . implode('; ', array_slice($this->dilewati, 0, 5))
                  . (count($this->dilewati) > 5 ? '; dan ' . (count($this->dilewati) - 5) . ' lainnya.' : '.');
        }
        return $msg;
    }
}
