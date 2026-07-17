<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            AdminSeeder::class,

            BeritaSeeder::class,

            GuruSeeder::class,

            GaleriSeeder::class,

            GelombangPendaftaranSeeder::class,

            PeriodeSeeder::class,

            JadwalPendaftaranSeeder::class,

            PembayaranSeeder::class,

            PromoSeeder::class,

        ]);
    }
}
