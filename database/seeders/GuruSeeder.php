<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        Guru::create([
            'foto' => 'default.jpg',
            'nama_lengkap' => 'KH. Ahmad Fauzi',
            'kategori' => 'Madrasah Diniyah',
            'mapel_bidang' => 'Nahwu & Fiqih',
            'pendidikan' => 'Alumni Sidogiri',
            'status' => 'Aktif',
            'alamat' => 'JL Kecamatan',
        ]);

        Guru::create([
            'foto' => 'default.jpg',
            'nama_lengkap' => 'Ali Murtadho,S.Pd',
            'kategori' => 'SMP',
            'mapel_bidang' => 'Seni Budaya',
            'pendidikan' => 'S1 Senirupa Terapan UNESA',
            'status' => 'Aktif',
            'alamat' => 'JL Pesantren',
        ]);

        Guru::create([
            'foto' => 'default.jpg',
            'nama_lengkap' => 'Danar Adrian Ridho Nugroho',
            'kategori' => 'SMK',
            'mapel_bidang' => 'UI/UX Designer',
            'pendidikan' => 'D4 Manajemen Informatika UNESA',
            'status' => 'Aktif',
            'alamat' => 'JL Pesantren',
        ]);
    }
}
