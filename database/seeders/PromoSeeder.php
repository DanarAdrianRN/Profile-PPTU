<?php

namespace Database\Seeders;

use App\Models\GelombangPendaftaran;
use App\Models\Pembayaran;
use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gelombangSatu = GelombangPendaftaran::where('nama_gelombang', 'Gelombang 1')->first();
        $gelombangDua = GelombangPendaftaran::where('nama_gelombang', 'Gelombang 2')->first();

        if ($gelombangSatu) {
            $promoSeragam = Promo::create([
                'gelombang_pendaftaran_id' => $gelombangSatu->id,
                'nama_promo' => 'Gratis 3 Stel Seragam',
                'tipe' => 'gratis_biaya',
                'nilai' => 0,
                'cakupan' => 'gelombang',
                'tanggal_mulai' => $gelombangSatu->tanggal_mulai,
                'tanggal_selesai' => $gelombangSatu->tanggal_selesai,
                'kuota' => 15,
                'terpakai' => 0,
                'keterangan' => 'Gratis 3 stel seragam untuk 15 pendaftar pertama',
                'is_active' => true,
            ]);

            $seragamIds = Pembayaran::where('nama_pembayaran', 'Seragam Pondok')->pluck('id');
            $promoSeragam->pembayarans()->sync($seragamIds);
        }

        if ($gelombangDua) {
            $promoDsp = Promo::create([
                'gelombang_pendaftaran_id' => $gelombangDua->id,
                'nama_promo' => 'Potongan DSP 10%',
                'tipe' => 'persentase',
                'nilai' => 10,
                'cakupan' => 'gelombang',
                'tanggal_mulai' => $gelombangDua->tanggal_mulai,
                'tanggal_selesai' => $gelombangDua->tanggal_selesai,
                'kuota' => null,
                'terpakai' => 0,
                'keterangan' => 'Bonus kitab dan perlengkapan santri',
                'is_active' => true,
            ]);

            $dspIds = Pembayaran::where('nama_pembayaran', 'Pengembangan Pendidikan')->pluck('id');
            $promoDsp->pembayarans()->sync($dspIds);
        }
    }
}
