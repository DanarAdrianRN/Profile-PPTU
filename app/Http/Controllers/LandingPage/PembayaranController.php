<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\GelombangPendaftaran;
use App\Models\JadwalPendaftaran;
use App\Models\Pembayaran;
use App\Models\Periode;

class PembayaranController extends Controller
{
    public function index()
    {

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $pembayarans = Pembayaran::all();

        /*
        |--------------------------------------------------------------------------
        | SMP
        |--------------------------------------------------------------------------
        */

        $smpTahunan = Pembayaran::where('jenjang', 'SMP')
            ->where('kategori', 'Biaya Tahunan')
            ->where('is_active', true)
            ->get();

        $smpBulanan = Pembayaran::where('jenjang', 'SMP')
            ->where('kategori', 'Biaya Bulanan')
            ->where('is_active', true)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SMK
        |--------------------------------------------------------------------------
        */

        $smkTahunan = Pembayaran::where('jenjang', 'SMK')
            ->where('kategori', 'Biaya Tahunan')
            ->where('is_active', true)
            ->get();

        $smkBulanan = Pembayaran::where('jenjang', 'SMK')
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

        $promos = $gelombangAktif?->promos()
            ->where('is_active', true)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | SEMUA GELOMBANG
        |--------------------------------------------------------------------------
        */

        $gelombangs = GelombangPendaftaran::with('promos')
            ->where('is_publish', true)
            ->orderBy('urutan')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN BANNER
        |--------------------------------------------------------------------------
        */

        $periodeAktif = Periode::aktif()->first();

        $jadwalPendaftarans = JadwalPendaftaran::publish()
            ->when($periodeAktif, function ($query) use ($periodeAktif) {
                $query->where(function ($query) use ($periodeAktif) {
                    $query->where('periode_id', $periodeAktif->id)
                        ->orWhereNull('periode_id');
                });
            })
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
