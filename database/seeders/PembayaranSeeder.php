<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

            /*
            |--------------------------------------------------------------------------
            | SMP - BIAYA TAHUNAN
            |--------------------------------------------------------------------------
            */

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Pendaftaran Pondok',
                'nominal' => 60000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Pendaftaran dan Seragam SMK',
                'nominal' => 1000000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Imtihan Madin & Madrasah Al-Qur\'an',
                'nominal' => 80000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Rapot Madin dan Rapot Al-Qur\'an',
                'nominal' => 45000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Majmu\' Kabir',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Manaqib',
                'nominal' => 15000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Yanbu\'a',
                'nominal' => 25000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Al-Qur\'an',
                'nominal' => 70000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Meja Lipat',
                'nominal' => 40000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Kasur',
                'nominal' => 365000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Seragam Pondok',
                'nominal' => 750000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Peralatan Kebersihan',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Peralatan Dapur',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Ekstrakurikuler',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Akhirussanah',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Ziaroh Santri',
                'nominal' => 350000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Pengembangan Pendidikan',
                'nominal' => 250000,
            ],

            /*
            |--------------------------------------------------------------------------
            | SMP - BIAYA BULANAN
            |--------------------------------------------------------------------------
            */

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Bulanan',
                'nama_pembayaran' => 'Uang Kesantrian',
                'nominal' => 155000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Bulanan',
                'nama_pembayaran' => 'Makan 3x Sehari',
                'nominal' => 350000,
            ],

            [
                'jenjang' => 'SMP',
                'kategori' => 'Biaya Bulanan',
                'nama_pembayaran' => 'SPP SMP',
                'nominal' => 145000,
            ],

            /*
            |--------------------------------------------------------------------------
            | SMK - BIAYA TAHUNAN
            |--------------------------------------------------------------------------
            */

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Registrasi Pondok',
                'nominal' => 60000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Pendaftaran dan Seragam SMK',
                'nominal' => 950000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Imtihan Madin & Madrasah Al-Qur\'an',
                'nominal' => 80000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Rapot Madin dan Rapot Al-Qur\'an',
                'nominal' => 45000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Majmu\' Kabir',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Manaqib',
                'nominal' => 15000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Yanbu\'a',
                'nominal' => 25000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Al-Qur\'an',
                'nominal' => 70000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Meja Lipat',
                'nominal' => 40000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Kasur',
                'nominal' => 365000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Seragam Pondok',
                'nominal' => 750000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Peralatan Kebersihan',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Peralatan Dapur',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Ekstrakurikuler',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Akhirussanah',
                'nominal' => 50000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Ziaroh Santri',
                'nominal' => 350000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Tahunan',
                'nama_pembayaran' => 'Pengembangan Pendidikan',
                'nominal' => 250000,
            ],


            /*
            |--------------------------------------------------------------------------
            | SMK - BIAYA BULANAN
            |--------------------------------------------------------------------------
            */

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Bulanan',
                'nama_pembayaran' => 'Uang Kesantrian',
                'nominal' => 155000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Bulanan',
                'nama_pembayaran' => 'Makan 3x Sehari',
                'nominal' => 350000,
            ],

            [
                'jenjang' => 'SMK',
                'kategori' => 'Biaya Bulanan',
                'nama_pembayaran' => 'SPP SMK',
                'nominal' => 195000,
            ],
        ];

        foreach ($data as $item) {

            Pembayaran::create([

                'jenjang' => $item['jenjang'],

                'kategori' => $item['kategori'],

                'nama_pembayaran' => $item['nama_pembayaran'],

                'nominal' => $item['nominal'],

                'is_active' => true,
            ]);
        }
    }
}