<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            $table->string('aksi');
            $table->text('deskripsi');

            // Referensi polymorphic opsional ke data terkait (Pendaftaran,
            // PendaftaranHasilTes, dll) — untuk pengembangan lanjutan
            // (mis. link "lihat data" dari log aktivitas).
            $table->string('subjek_type')->nullable();
            $table->unsignedBigInteger('subjek_id')->nullable();

            $table->timestamps();

            $table->index(['subjek_type', 'subjek_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};