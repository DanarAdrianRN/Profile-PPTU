<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gelombang_pendaftarans', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->nullOnDelete();
        });

        if (! Schema::hasColumn('pendaftarans', 'periode_id')) {
            return;
        }

        // Only infer historical links when the registrants identify one period unambiguously.
        $periods = DB::table('pendaftarans')
            ->select('gelombang_pendaftaran_id')
            ->selectRaw('MIN(periode_id) as periode_id')
            ->whereNotNull('gelombang_pendaftaran_id')
            ->whereNotNull('periode_id')
            ->groupBy('gelombang_pendaftaran_id')
            ->havingRaw('COUNT(DISTINCT periode_id) = 1')
            ->get();

        foreach ($periods as $period) {
            DB::table('gelombang_pendaftarans')->where('id', $period->gelombang_pendaftaran_id)
                ->update(['periode_id' => $period->periode_id]);
        }
    }

    public function down(): void
    {
        Schema::table('gelombang_pendaftarans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('periode_id');
        });
    }
};
