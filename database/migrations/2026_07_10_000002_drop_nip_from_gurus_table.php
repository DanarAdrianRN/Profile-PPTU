<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            if (Schema::hasColumn('gurus', 'nip')) {
                $table->dropColumn('nip');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            if (! Schema::hasColumn('gurus', 'nip')) {
                $table->string('nip')->nullable()->after('nama_lengkap');
            }
        });
    }
};
