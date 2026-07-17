<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')
                ->constrained('transaksis')
                ->cascadeOnDelete();
            $table->foreignId('tagihan_santri_detail_id')
                ->constrained('tagihan_santri_details')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('nominal')->default(0);
            $table->timestamps();

            $table->unique([
                'transaksi_id',
                'tagihan_santri_detail_id',
            ], 'transaksi_detail_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
