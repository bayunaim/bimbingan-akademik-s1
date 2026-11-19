<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->foreignId('mahasiswa_id')->nullable()->after('id')
                  ->constrained('users')->nullOnDelete();
            $table->foreignId('dosen_id')->nullable()->after('mahasiswa_id')
                  ->constrained('users')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->after('dosen_id')
                  ->constrained('prodis')->nullOnDelete();
        });
        // Kolom nama_mahasiswa / nama_dosen / nim / nip_dosen tetap dipertahankan
        // sebagai "snapshot" yang tercetak di dokumen, tapi akses data
        // sekarang memakai mahasiswa_id / dosen_id / prodi_id.
    }

    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prodi_id');
            $table->dropConstrainedForeignId('dosen_id');
            $table->dropConstrainedForeignId('mahasiswa_id');
        });
    }
};
