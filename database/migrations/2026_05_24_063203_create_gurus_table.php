<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('nama_lengkap');
            $table->enum('kategori', [
                'SMP',
                'SMK',
                'Madrasah Diniyah',
                "Madrasah Al-Qur'an",
                'TPQ',
            ]);
            $table->string('mapel_bidang');
            $table->string('pendidikan');
            $table->enum('status', [
                'Aktif',
                'Nonaktif'
            ])->default('Aktif');
            $table->text('alamat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
