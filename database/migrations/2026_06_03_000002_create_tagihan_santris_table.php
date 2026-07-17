<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_santris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')
                ->unique()
                ->constrained('pendaftarans')
                ->cascadeOnDelete();
            $table->foreignId('gelombang_pendaftaran_id')
                ->nullable()
                ->constrained('gelombang_pendaftarans')
                ->nullOnDelete();
            $table->string('kode_tagihan')->unique();
            $table->enum('jenjang', [
                'SMP',
                'SMK',
            ]);
            $table->unsignedBigInteger('nominal_awal')->default(0);
            $table->unsignedBigInteger('potongan_promo')->default(0);
            $table->unsignedBigInteger('nominal_akhir')->default(0);
            $table->unsignedBigInteger('total_dibayar')->default(0);
            $table->unsignedBigInteger('sisa_tagihan')->default(0);
            $table->enum('status_pembayaran', [
                'belum_dibayar',
                'dicicil',
                'lunas',
                'dibatalkan',
            ])->default('belum_dibayar');
            $table->boolean('boleh_dicicil')->default(true);
            $table->unsignedTinyInteger('jumlah_cicilan')->default(1);
            $table->unsignedTinyInteger('cicilan_terbayar')->default(0);
            $table->unsignedBigInteger('nominal_per_cicilan')->default(0);
            $table->date('jatuh_tempo')->nullable();
            $table->json('promo_snapshot')->nullable();
            $table->timestamps();
        });

        Schema::create('tagihan_santri_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_santri_id')
                ->constrained('tagihan_santris')
                ->cascadeOnDelete();
            $table->foreignId('pembayaran_id')
                ->nullable()
                ->constrained('pembayarans')
                ->nullOnDelete();
            $table->foreignId('promo_id')
                ->nullable()
                ->constrained('promos')
                ->nullOnDelete();
            $table->string('nama_pembayaran');
            $table->string('kategori');
            $table->unsignedBigInteger('nominal_awal')->default(0);
            $table->unsignedBigInteger('potongan_promo')->default(0);
            $table->unsignedBigInteger('nominal_akhir')->default(0);
            $table->string('nama_promo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_santri_details');
        Schema::dropIfExists('tagihan_santris');
    }
};
