<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeris', function (Blueprint $table) {

            $table->id();
            $table->string('judul');
            $table->string('thumbnail');
            $table->date('tanggal_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->enum('status', [
                'Publish',
                'Draft',
            ])->default('Draft');

            $table->unsignedBigInteger('created_by_admin_id')
                ->nullable();
            $table->unsignedBigInteger('updated_by_admin_id')
                ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};