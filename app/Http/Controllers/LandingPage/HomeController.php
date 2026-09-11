<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\GelombangPendaftaran;
use App\Models\Periode;
use App\Models\Promo;

class HomeController extends Controller
{
    public function index()
    {
        $periodeAktif = Periode::aktif()->first();

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

        $jadwalPendaftarans = $periodeAktif?->jadwalPendaftarans()
            ->publish()
            ->orderBy('urutan')
            ->orderBy('tanggal')
            ->get() ?? collect();

        $showModal = !is_null($gelombangAktif);

        return view(
            'pages.landing-page.home',
            compact(
                'periodeAktif',
                'gelombangAktif',
                'promos',
                'jadwalPendaftarans',
                'showModal'
            )
        );
    }
}
