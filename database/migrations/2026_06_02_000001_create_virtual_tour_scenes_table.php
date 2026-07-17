<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('virtual_tour_scenes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('panorama')->nullable();
            $table->enum('status', [
                'published',
                'draft',
                'hidden',
            ])->default('draft');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('is_start_scene')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_tour_scenes');
    }
};
