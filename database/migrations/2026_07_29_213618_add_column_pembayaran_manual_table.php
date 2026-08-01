<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (! Schema::hasColumn('transaksis', 'sumber_pembayaran')) {
                // 'midtrans' (default, via gateway) atau 'manual' (dicatat admin: tunai/transfer manual)
                $table->string('sumber_pembayaran')->default('midtrans')->after('status');
            }

            if (! Schema::hasColumn('transaksis', 'dicatat_oleh_admin_id')) {
                $table->foreignId('dicatat_oleh_admin_id')
                    ->nullable()
                    ->after('sumber_pembayaran')
                    ->constrained('admins')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('transaksis', 'catatan')) {
                $table->text('catatan')->nullable()->after('dicatat_oleh_admin_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'dicatat_oleh_admin_id')) {
                $table->dropConstrainedForeignId('dicatat_oleh_admin_id');
            }

            if (Schema::hasColumn('transaksis', 'catatan')) {
                $table->dropColumn('catatan');
            }

            if (Schema::hasColumn('transaksis', 'sumber_pembayaran')) {
                $table->dropColumn('sumber_pembayaran');
            }
        });
    }
};