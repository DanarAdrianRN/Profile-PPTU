<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftaran_dokumens', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pendaftaran_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('jenis_dokumen');

            $table->string('file');

            $table->string('status_verifikasi')
                ->default('Menunggu');

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_dokumens');
    }
};
