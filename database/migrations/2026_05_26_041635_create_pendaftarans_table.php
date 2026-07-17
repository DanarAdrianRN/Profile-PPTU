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
        Schema::create('pendaftarans', function (Blueprint $table) {

            $table->id();
            $table->foreignId('gelombang_pendaftaran_id')
                ->nullable()
                ->constrained('gelombang_pendaftarans')
                ->nullOnDelete();

            // IDENTITAS
            $table->string('kode_pendaftaran')->unique();
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('kewarganegaraan')->nullable();
            $table->integer('anak_ke')->nullable();
            $table->integer('jumlah_saudara_kandung')->nullable();
            $table->integer('jumlah_saudara_angkat')->nullable();
            $table->integer('jumlah_saudara_tiri')->nullable();
            $table->string('status_anak')->nullable();
            $table->string('bahasa_rumah')->nullable();

            // ALAMAT
            $table->text('alamat')->nullable();
            $table->string('rt_rw')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('tempat_tinggal')->nullable();
            $table->string('jarak_rumah')->nullable();
            $table->string('no_hp_ortu')->nullable();

            // KESEHATAN
            $table->string('berat_badan')->nullable();
            $table->string('tinggi_badan')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('kelainan_jasmani')->nullable();

            // KEPESANTRENAN
            $table->string('kemampuan_quran')->nullable();
            $table->string('hafalan')->nullable();
            $table->boolean('baca_pegon')->default(false);
            $table->boolean('tulis_pegon')->default(false);

            // MINAT
            $table->text('bakat_prestasi')->nullable();
            $table->json('ekstrakurikuler')->nullable();

            // FINAL
            $table->string('size_seragam_pondok')->nullable();
            $table->string('size_seragam_formal')->nullable();
            $table->json('sumber_info')->nullable();

            // STATUS
            $table->enum('status', [
                'menunggu_verifikasi',
                'diterima',
                'ditolak',
            ])->default('menunggu_verifikasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
