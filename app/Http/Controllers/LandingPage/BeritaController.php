<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $kategori = trim((string) $request->query('kategori', ''));
        $pencarian = trim((string) $request->query('q', ''));

        $latestBerita = Berita::query()
            ->where('status', 'Publish')
            ->latest('tanggal_publish')
            ->latest('id')
            ->first();

        $beritas = Berita::query()
            ->where('status', 'Publish')
            ->when($kategori !== '', function ($query) use ($kategori) {
                $query->where('kategori', $kategori);
            })
            ->when($pencarian !== '', function ($query) use ($pencarian) {
                $query->where('judul', 'like', '%' . $pencarian . '%');
            })
            ->when($kategori === '' && $pencarian === '' && $latestBerita, function ($query) use ($latestBerita) {
                $query->where('id', '!=', $latestBerita->id);
            })
            ->latest('tanggal_publish')
            ->latest('id')
            ->paginate(6)
            ->withQueryString()
            ->fragment('daftar-berita');

        $featuredBerita = $kategori === '' && $pencarian === '' && $beritas->currentPage() === 1
            ? $latestBerita
            : null;

        $kategoriCounts = $this->kategoriCounts();

        return view('pages.landing-page.berita.berita', compact(
            'featuredBerita',
            'beritas',
            'kategoriCounts',
            'kategori',
            'pencarian'
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

        $kategoriCounts = $this->kategoriCounts();

        return view('pages.landing-page.berita.detail-berita', compact(
            'berita',
            'relatedBeritas',
            'kategoriCounts'
        ));
    }

    private function kategoriCounts()
    {
        return Berita::query()
            ->where('status', 'Publish')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->orderBy('kategori')
            ->pluck('total', 'kategori');
    }
}
