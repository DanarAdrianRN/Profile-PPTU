<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('virtual_tour_hotspots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('virtual_tour_scene_id')
                ->constrained('virtual_tour_scenes')
                ->cascadeOnDelete();
            $table->foreignId('target_scene_id')
                ->nullable()
                ->constrained('virtual_tour_scenes')
                ->nullOnDelete();
            $table->enum('tipe', [
                'navigation',
                'information',
            ])->default('navigation');
            $table->enum('icon', [
                'arrow-right',
                'arrow-up',
                'arrow-down',
                'arrow-left',
                'info',
                'door',
                'camera',
            ])->default('arrow-right');
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('yaw', 8, 4)->default(0);
            $table->decimal('pitch', 8, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_tour_hotspots');
    }
};
