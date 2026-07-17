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
        Schema::create('landing_banners', function (Blueprint $table) {
            $table->id();

            $table->string('tahun_ajaran');

            $table->string('promo_atas');
            $table->string('promo_bawah');
            $table->string('kuota_promo');

            $table->text('subtitle');
            $table->date('tanggal_tes')->nullable();
            $table->date('tanggal_pengumuman')->nullable();
            $table->date('tanggal_daftar_ulang')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_banners');
    }
};
