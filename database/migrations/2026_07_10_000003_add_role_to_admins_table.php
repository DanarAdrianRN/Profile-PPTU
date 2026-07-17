<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'role')) {
                $table->enum('role', ['administrasi', 'media'])
                    ->default('administrasi')
                    ->after('username');
            }
        });

        DB::table('admins')
            ->where('username', 'adminMedia')
            ->update(['role' => 'media']);

        DB::table('admins')
            ->where('username', 'adminYayasan')
            ->update(['role' => 'administrasi']);
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
