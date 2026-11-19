<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->string('file_pdf')->nullable()->after('kegiatan_pengesahan');
            // Statusnya bisa: Menunggu Review, Revisi, Selesai
            $table->string('status')->default('Menunggu Review')->after('file_pdf');
        });
    }

    public function down()
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->dropColumn(['file_pdf', 'status']);
        });
    }
};
