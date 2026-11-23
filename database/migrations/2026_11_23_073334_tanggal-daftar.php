<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memiliki', function (Blueprint $table) {
            // Waktu user mendaftar beasiswa.
            // useCurrent() -> default CURRENT_TIMESTAMP, jadi baris lama
            // otomatis terisi saat migration dijalankan dan insert baru
            // tidak wajib mengisi manual.
            $table->dateTime('TanggalDaftar')
                ->useCurrent()
                ->after('NamaFile');
        });
    }

    public function down(): void
    {
        Schema::table('memiliki', function (Blueprint $table) {
            $table->dropColumn('TanggalDaftar');
        });
    }
};