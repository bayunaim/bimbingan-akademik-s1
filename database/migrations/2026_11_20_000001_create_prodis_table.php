<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();          // IKOR, PJKR, PKH
            $table->string('nama');                    // nama lengkap prodi (untuk kop cetak)
            $table->string('nama_kaprodi')->nullable();
            $table->string('nip_kaprodi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};
