<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tagihan_santri_details', function (Blueprint $table) {
            if (! Schema::hasColumn('tagihan_santri_details', 'status_pembayaran')) {
                $table->enum('status_pembayaran', [
                    'belum_dibayar',
                    'pending',
                    'lunas',
                ])->default('belum_dibayar')->after('nama_promo');
            }

            if (! Schema::hasColumn('tagihan_santri_details', 'tanggal_bayar')) {
                $table->timestamp('tanggal_bayar')->nullable()->after('status_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tagihan_santri_details', function (Blueprint $table) {
            if (Schema::hasColumn('tagihan_santri_details', 'tanggal_bayar')) {
                $table->dropColumn('tanggal_bayar');
            }

            if (Schema::hasColumn('tagihan_santri_details', 'status_pembayaran')) {
                $table->dropColumn('status_pembayaran');
            }
        });
    }
};