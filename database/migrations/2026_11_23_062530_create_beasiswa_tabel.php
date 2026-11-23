<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beasiswa', function (Blueprint $table) {
            $table->increments('id'); // INTEGER unsigned, auto increment
            $table->string('Nama', 255);
            $table->text('Deskripsi');
            $table->dateTime('Dibuka');
            $table->dateTime('Ditutup');
            $table->unsignedInteger('JumlahKursi');
            $table->unsignedInteger('SudahMasuk')->default(0);
            $table->enum('status', ['dibuka', 'ditutup'])->default('dibuka');
            $table->text('SyaratUtama');
            $table->text('Jenjang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beasiswa');
    }
};