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
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();

            $table->string('judul', 255);
            $table->string('slug', 255)->unique();
            $table->string('thumbnail');
            $table->string('gambar_detail_1')->nullable();
            $table->string('gambar_detail_2')->nullable();
            $table->longText('isi_berita');
            $table->string('blockquote')->nullable();
            $table->string('penulis', 255);
            $table->enum('kategori', ['Pendaftaran', 'Prestasi', 'Kegiatan', 'Pengumuman']);
            $table->enum('status', ['Draft', 'Publish'])->default('Draft');
            $table->date('tanggal_publish');

            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            $table->unsignedBigInteger('updated_by_admin_id')->nullable();

            $table->timestamps();

            $table->index(['tanggal_publish', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
