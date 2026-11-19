<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom ini fleksibel, bisa nyimpen NIM (kalau mhs) atau NIP (kalau dosen)
            $table->string('nomor_induk')->nullable()->after('email');

            // Kolom untuk Plotting: menyimpan ID Dosen Pembimbing untuk si mahasiswa
            $table->unsignedBigInteger('dosen_id')->nullable()->after('role');

            // Relasi ke tabel users itu sendiri (karena dosen juga ada di tabel users)
            $table->foreign('dosen_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dosen_id']);
            $table->dropColumn(['nomor_induk', 'dosen_id']);
        });
    }
};
