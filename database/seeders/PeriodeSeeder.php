<?php

namespace Database\Seeders;

use App\Models\Periode;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        Periode::create([
            'nama_periode' => 'Pendaftaran Santri Baru Tahun Ajaran 2026-2027',
            'is_active' => true,
        ]);
    }
}
