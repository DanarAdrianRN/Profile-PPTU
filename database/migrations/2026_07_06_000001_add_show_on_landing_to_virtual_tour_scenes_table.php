<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('virtual_tour_scenes', function (Blueprint $table) {
            $table->boolean('show_on_landing')
                ->default(false)
                ->after('is_start_scene');
        });
    }

    public function down(): void
    {
        Schema::table('virtual_tour_scenes', function (Blueprint $table) {
            $table->dropColumn('show_on_landing');
        });
    }
};
