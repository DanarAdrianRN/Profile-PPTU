<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')
                ->nullable()
                ->constrained('periodes')
                ->nullOnDelete();
            $table->string('nama_jadwal');
            $table->date('tanggal');
            $table->string('icon')->default('fa-regular fa-calendar');
            $table->unsignedInteger('urutan')->default(1);
            $table->boolean('is_publish')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pendaftarans');
    }
};
