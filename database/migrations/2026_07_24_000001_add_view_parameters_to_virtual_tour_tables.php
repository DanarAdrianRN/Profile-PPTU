<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('virtual_tour_scenes', function (Blueprint $table) {
            $table->decimal('initial_yaw', 8, 4)->default(0)->after('is_start_scene');
            $table->decimal('initial_pitch', 8, 4)->default(0)->after('initial_yaw');
            $table->decimal('initial_fov', 8, 4)->default(1.5708)->after('initial_pitch');
        });

        Schema::table('virtual_tour_hotspots', function (Blueprint $table) {
            $table->decimal('target_yaw', 8, 4)->nullable()->after('pitch');
            $table->decimal('target_pitch', 8, 4)->nullable()->after('target_yaw');
            $table->decimal('target_fov', 8, 4)->nullable()->after('target_pitch');
        });
    }

    public function down(): void
    {
        Schema::table('virtual_tour_hotspots', function (Blueprint $table) {
            $table->dropColumn([
                'target_yaw',
                'target_pitch',
                'target_fov',
            ]);
        });

        Schema::table('virtual_tour_scenes', function (Blueprint $table) {
            $table->dropColumn([
                'initial_yaw',
                'initial_pitch',
                'initial_fov',
            ]);
        });
    }
};
