<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\GelombangPendaftaran;
use App\Models\JadwalPendaftaran;
use App\Models\Pembayaran;
use App\Models\Periode;
use App\Models\Promo;

class MasterPembayaranController extends Controller
{
    public function index()
    {

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $pembayarans = Pembayaran::periodeAktif()->get();

        /*
        |--------------------------------------------------------------------------
        | SMP
        |--------------------------------------------------------------------------
        */

        $smpTahunan = Pembayaran::periodeAktif()->where('jenjang', 'SMP')
            ->where('kategori', 'Biaya Tahunan')
            ->where('is_active', true)
            ->get();

        $smpBulanan = Pembayaran::periodeAktif()->where('jenjang', 'SMP')
            ->where('kategori', 'Biaya Bulanan')
            ->where('is_active', true)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SMK
        |--------------------------------------------------------------------------
        */

        $smkTahunan = Pembayaran::periodeAktif()->where('jenjang', 'SMK')
            ->where('kategori', 'Biaya Tahunan')
            ->where('is_active', true)
            ->get();

        $smkBulanan = Pembayaran::periodeAktif()->where('jenjang', 'SMK')
            ->where('kategori', 'Biaya Bulanan')
            ->where('is_active', true)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GELOMBANG AKTIF
        |--------------------------------------------------------------------------
        */

        $gelombangAktif = GelombangPendaftaran::aktif()
            ->orderBy('urutan')
            ->first();

        $promos = $gelombangAktif
            ? Promo::periodeAktif()->where('is_active', true)
                ->where(function ($query) use ($gelombangAktif) {
                    $query->whereNull('gelombang_pendaftaran_id')
                        ->orWhere('gelombang_pendaftaran_id', $gelombangAktif->id);
                })
                ->first()
            : null;

        /*
        |--------------------------------------------------------------------------
        | SEMUA GELOMBANG
        |--------------------------------------------------------------------------
        */

        $gelombangs = GelombangPendaftaran::periodeAktif()->with(['promos' => fn ($query) => $query->periodeAktif()->where('is_active', true)])
            ->where('is_publish', true)
            ->orderBy('urutan')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN BANNER
        |--------------------------------------------------------------------------
        */

        $promoSemuaGelombang = Promo::periodeAktif()
            ->where('is_active', true)
            ->whereNull('gelombang_pendaftaran_id')
            ->get();

        foreach ($gelombangs as $gelombang) {
            $gelombang->setRelation('promos', $gelombang->promos->merge($promoSemuaGelombang));
        }

        $periodeAktif = Periode::aktif()->first();

        $jadwalPendaftarans = JadwalPendaftaran::periodeAktif()->publish()
            ->orderBy('urutan')
            ->orderBy('tanggal')
            ->get();

        return view(
            'pages.landing-page.pendaftaran.info-pendaftaran',
            compact(

                'periodeAktif',
                'jadwalPendaftarans',
                'pembayarans',

                'smpTahunan',
                'smpBulanan',

                'smkTahunan',
                'smkBulanan',

                'gelombangAktif',
                'gelombangs',

                'promos'
            )
        );
    }
}
