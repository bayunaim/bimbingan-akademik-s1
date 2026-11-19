<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // role dijadikan string biasa supaya bisa menampung 'kaprodi'
            // (enum di SQLite/MySQL sulit diubah lewat migration)
            $table->string('role')->default('mahasiswa')->change();

            $table->foreignId('prodi_id')->nullable()->after('role')
                  ->constrained('prodis')->nullOnDelete();
            $table->dropColumn('prodi'); // teks bebas lama diganti prodi_id
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prodi_id');
            $table->string('prodi')->nullable();
        });
    }
};
