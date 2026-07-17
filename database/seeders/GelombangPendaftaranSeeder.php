<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GelombangPendaftaran;

class GelombangPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GelombangPendaftaran::create([
            'nama_gelombang' => 'Gelombang 1',

            'tanggal_mulai' => now()->subDays(3),

            'tanggal_selesai' => now()->addDays(20),

            'urutan' => 1,

            'is_publish' => true,
        ]);

        GelombangPendaftaran::create([
            'nama_gelombang' => 'Gelombang 2',

            'tanggal_mulai' => now()->addDays(21),

            'tanggal_selesai' => now()->addDays(45),

            'urutan' => 2,

            'is_publish' => true,
        ]);
    }
}
