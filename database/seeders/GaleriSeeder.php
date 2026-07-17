<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        Galeri::insert([

            [
                'id' => 1,
                'judul' => 'Haul 2025',
                'thumbnail' => 'galeri1.jpg',
                'tanggal_kegiatan' => '2026-05-12',
                'deskripsi' => 'Dokumentasi kegiatan haul 2025',
                'status' => 'Publish',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 2,
                'judul' => 'Kegiatan Madrasah Diniyah',
                'thumbnail' => 'galeri2.jpg',
                'tanggal_kegiatan' => '2026-05-18',
                'deskripsi' => 'Kegiatan pembelajaran di madrasah diniyah',
                'status' => 'Publish',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 3,
                'judul' => 'Workshop DKV',
                'thumbnail' => 'galeri3.jpg',
                'tanggal_kegiatan' => '2026-05-21',
                'deskripsi' => 'Workshop desain komunikasi visual untuk siswa SMK',
                'status' => 'Publish',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 4,
                'judul' => 'Praktik TBSM',
                'thumbnail' => 'galeri4.jpg',
                'tanggal_kegiatan' => '2026-05-25',
                'deskripsi' => 'Praktik teknik kendaraan ringan di bengkel sekolah',
                'status' => 'Publish',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}