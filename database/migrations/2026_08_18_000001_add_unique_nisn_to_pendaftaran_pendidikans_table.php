<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_pendidikans', function (Blueprint $table) {
            $table->unique('nisn', 'pendaftaran_pendidikans_nisn_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_pendidikans', function (Blueprint $table) {
            $table->dropUnique('pendaftaran_pendidikans_nisn_unique');
        });
    }
};
