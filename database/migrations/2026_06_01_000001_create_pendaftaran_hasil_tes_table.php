<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_hasil_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')
                ->unique()
                ->constrained('pendaftarans')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('baca_tulis_pegon')->nullable();
            $table->unsignedTinyInteger('doa_harian')->nullable();
            $table->unsignedTinyInteger('ubudiyyah')->nullable();
            $table->unsignedTinyInteger('membaca_al_quran')->nullable();
            $table->unsignedTinyInteger('hafalan_surat_pendek')->nullable();
            $table->string('wawancara')->nullable();
            $table->enum('status_kelulusan', [
                'proses',
                'diterima',
                'cadangan',
                'tidak_diterima',
            ])->default('proses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_hasil_tes');
    }
};
