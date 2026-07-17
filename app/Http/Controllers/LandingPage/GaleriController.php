<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;

class GaleriController extends Controller
{
    public function index()
    {
        $galeris = Galeri::with('fotos')
            ->where('status', 'Publish')
            ->latest('tanggal_kegiatan')
            ->get();

        return view(
            'pages.landing-page.galeri',
            compact('galeris')
        );
    }
}