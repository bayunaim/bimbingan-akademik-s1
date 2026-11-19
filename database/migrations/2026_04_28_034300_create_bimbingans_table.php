<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bimbingans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mahasiswa');
            $table->string('nim');
            $table->string('semester');
            $table->string('tahun_akademik');
            $table->string('nama_dosen');
            $table->string('nip_dosen');
            $table->string('jenis_bimbingan'); // e.g., "Konsultasi Awal Perkuliahan"
            $table->text('catatan_umum')->nullable();
            $table->integer('jumlah_bimbingan')->default(0);
            $table->string('kegiatan_pengesahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingans');
    }
};
