<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\GelombangPendaftaran;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Promo;
use App\Models\TagihanSantri;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarUlangController extends Controller
{
    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load([
            'pendidikan',
            'tagihanSantri.details.pembayaran',
            'tagihanSantri.details.promo',
        ]);

        $tagihan = $this->ensureTagihan($pendaftaran);

        return view('pages.landing-page.daftar-ulang.daftar-ulang', [
            'showModal' => false,
            'pendaftaran' => $pendaftaran,
            'tagihan' => $tagihan->load([
                'details.pembayaran',
                'details.promo',
            ]),
        ]);
    }
}
