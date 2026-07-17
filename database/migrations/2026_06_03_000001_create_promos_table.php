<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gelombang_pendaftaran_id')
                ->nullable()
                ->constrained('gelombang_pendaftarans')
                ->nullOnDelete();
            $table->string('nama_promo');
            $table->enum('tipe', [
                'nominal',
                'persentase',
                'gratis_biaya',
            ]);
            $table->unsignedBigInteger('nilai')->default(0);
            $table->enum('cakupan', [
                'semua',
                'gelombang',
                'jenjang',
                'aturan',
            ])->default('semua');
            $table->enum('jenjang', [
                'SMP',
                'SMK',
            ])->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->unsignedInteger('kuota')->nullable();
            $table->unsignedInteger('terpakai')->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('promo_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_id')
                ->constrained('promos')
                ->cascadeOnDelete();
            $table->foreignId('pembayaran_id')
                ->constrained('pembayarans')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique([
                'promo_id',
                'pembayaran_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_pembayaran');
        Schema::dropIfExists('promos');
    }
};
