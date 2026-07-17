<?php

namespace Database\Seeders;

use App\Models\JadwalPendaftaran;
use App\Models\Periode;
use Illuminate\Database\Seeder;

class JadwalPendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Periode::aktif()->first();

        $jadwals = [
            [
                'nama_jadwal' => 'Tes Seleksi',
                'tanggal' => '2026-06-20',
                'urutan' => 1,
            ],
            [
                'nama_jadwal' => 'Pengumuman',
                'tanggal' => '2026-06-25',
                'urutan' => 2,
            ],
            [
                'nama_jadwal' => 'Daftar Ulang',
                'tanggal' => '2026-06-26',
                'urutan' => 3,
            ],
        ];

        foreach ($jadwals as $jadwal) {
            JadwalPendaftaran::updateOrCreate(
                [
                    'nama_jadwal' => $jadwal['nama_jadwal'],
                    'periode_id' => $periode?->id,
                ],
                [
                    'tanggal' => $jadwal['tanggal'],
                    'urutan' => $jadwal['urutan'],
                    'is_publish' => true,
                ]
            );
        }
    }
}
