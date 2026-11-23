<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memiliki', function (Blueprint $table) {
            $table->increments('id');

            // users.id = BIGINT unsigned -> foreignId
            $table->foreignId('id_user')
                ->constrained('users')
                ->cascadeOnDelete();

            // beasiswa.id = INTEGER unsigned -> harus unsignedInteger, BUKAN foreignId
            $table->unsignedInteger('id_beasiswa');
            $table->foreign('id_beasiswa')
                ->references('id')
                ->on('beasiswa')
                ->cascadeOnDelete();

            $table->string('NamaUniversitas', 255);
            $table->string('Angkatan', 255);
            $table->text('AlamatUniv');
            $table->text('UploadFilePath');
            $table->text('NamaFile');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memiliki');
    }
};