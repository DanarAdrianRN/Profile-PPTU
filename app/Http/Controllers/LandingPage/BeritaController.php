<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $featuredBerita = Berita::query()
            ->where('status', 'Publish')
            ->latest('tanggal_publish')
            ->first();

        $beritas = Berita::query()
            ->where('status', 'Publish')
            ->when($featuredBerita, function ($query) use ($featuredBerita) {
                $query->where('id', '!=', $featuredBerita->id);
            })
            ->latest('tanggal_publish')
            ->get();

        return view('pages.landing-page.berita.berita', compact(
            'featuredBerita',
            'beritas'
        ));
    }

    public function detailBerita($slug)
    {
        $berita = Berita::query()
            ->where('slug', $slug)
            ->where('status', 'Publish')
            ->firstOrFail();

        $relatedBeritas = Berita::query()
            ->where('status', 'Publish')
            ->where('id', '!=', $berita->id)
            ->latest('tanggal_publish')
            ->take(3)
            ->get();

        $kategoriCounts = Berita::query()
            ->where('status', 'Publish')
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        return view('pages.landing-page.berita.detail-berita', compact(
            'berita',
            'relatedBeritas',
            'kategoriCounts'
        ));
}
}