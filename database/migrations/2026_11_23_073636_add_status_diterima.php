<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memiliki', function (Blueprint $table) {
            // Default 'menunggu' supaya baris lama dan pendaftaran baru
            // otomatis berstatus belum diproses.
            $table->enum('StatusDiterima', ['menunggu', 'diterima', 'ditolak'])
                ->default('menunggu')
                ->after('TanggalDaftar');
        });
    }

    public function down(): void
    {
        Schema::table('memiliki', function (Blueprint $table) {
            $table->dropColumn('StatusDiterima');
        });
    }
};