<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['pembayarans', 'promos'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->foreignId('periode_id')->nullable()->constrained('periodes')->restrictOnDelete();
            });
        }

        $activeId = DB::table('periodes')->where('is_active', true)->value('id');
        if ($activeId) {
            foreach (['gelombang_pendaftarans', 'jadwal_pendaftarans', 'pembayarans'] as $name) {
                DB::table($name)->whereNull('periode_id')->update(['periode_id' => $activeId]);
            }
        }

        // Linked promos follow their wave; previously global promos belong to the current period.
        foreach (DB::table('promos')->get(['id', 'gelombang_pendaftaran_id']) as $promo) {
            $periodeId = $promo->gelombang_pendaftaran_id
                ? DB::table('gelombang_pendaftarans')->where('id', $promo->gelombang_pendaftaran_id)->value('periode_id')
                : $activeId;
            DB::table('promos')->where('id', $promo->id)->update(['periode_id' => $periodeId]);
        }
    }

    public function down(): void
    {
        foreach (['promos', 'pembayarans'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropConstrainedForeignId('periode_id');
            });
        }
    }
};
