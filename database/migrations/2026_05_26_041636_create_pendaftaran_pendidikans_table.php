<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftaran_pendidikans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pendaftaran_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('jenjang_pendidikan')->nullable();

            $table->string('jurusan')->nullable();

            $table->string('sekolah_asal')->nullable();

            $table->year('tahun_lulus')->nullable();

            $table->string('tanggal_nomor_ijazah')->nullable();

            $table->string('nisn')->nullable();

            $table->string('lama_belajar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pendidikans');
    }
};
