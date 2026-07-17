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
        Schema::create('pendaftaran_orang_tuas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pendaftaran_id')
                ->constrained()
                ->cascadeOnDelete();

            // ayah | ibu | wali
            $table->enum('tipe', [
                'ayah',
                'ibu',
                'wali'
            ]);

            $table->string('nama')->nullable();

            $table->string('status')->nullable();

            $table->string('tempat_lahir')->nullable();

            $table->date('tanggal_lahir')->nullable();

            $table->string('agama')->nullable();

            $table->string('pendidikan')->nullable();

            $table->string('pekerjaan')->nullable();

            $table->string('penghasilan')->nullable();

            $table->text('alamat')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_orang_tuas');
    }
};
