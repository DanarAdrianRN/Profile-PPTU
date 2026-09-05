<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\GelombangPendaftaran;
use App\Models\Periode;

class HomeController extends Controller
{
    public function index()
    {
        $periodeAktif = Periode::aktif()->first();

        $gelombangAktif = GelombangPendaftaran::aktif()
            ->orderBy('urutan')
            ->first();

        $promos = $gelombangAktif?->promos()
            ->where('is_active', true)
            ->first();

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
