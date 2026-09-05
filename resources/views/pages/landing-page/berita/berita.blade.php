@extends('layout.app')
@section('content')
@include('components.header')
    <section class="berita">
        <div class="container">
            {{-- TITLE --}}
            <div class="section-heading">
                <span class="label">
                    <i class="fa-solid fa-newspaper"></i>
                    Informasi Yayasan
                </span>
                <h2>
                    Berita Terkini
                </h2>
                <p>
                    Informasi terbaru seputar kegiatan, pengumuman,
                    prestasi, dan agenda Yayasan Tarbiyatul 'Ulum.
                </p>
            </div>
            {{-- FEATURED NEWS --}}
            @if ($featuredBerita)
                <div class="featured-news">

                    <div class="featured-image">
                        <img src="{{ asset('storage/' . $featuredBerita->thumbnail) }}" alt="{{ $featuredBerita->judul }}">

                        <div class="category">
                            {{ $featuredBerita->kategori }}
                        </div>
                    </div>

                    <div class="featured-content">

                        <div class="meta">

                            <span>
                                <i class="fa-solid fa-calendar-days"></i>
                                {{ $featuredBerita->tanggal_publish->format('d M Y') }}
                            </span>

                        </div>

                        <h3>
                            {{ $featuredBerita->judul }}
                        </h3>

                        <p>
                            {{ Str::limit($featuredBerita->isi_berita, 180) }}
                        </p>

                        <a href="{{ route('detail-berita', $featuredBerita->slug) }}" class="btn-detail">

                            Baca Selengkapnya
                            <i class="fa-solid fa-arrow-right"></i>

                        </a>

                    </div>

                </div>
            @endif
            {{-- BERITA LIST --}}
            <div class="news-wrapper" id="daftar-berita">
                {{-- FILTER --}}
                <div class="news-filter">
                    <div class="filter-title">
                        <h3>Berita & Pengumuman</h3>
                        <p>
                            Cari dan filter informasi terbaru Pondok Pesantren Tarbiyatul 'Ulum Sumursongo sesuai kategori yang diinginkan.
                        </p>
                    </div>
                    <div class="filter-action">
                        {{-- SEARCH --}}
                        <form class="search-box" action="{{ route('berita') }}" method="GET">
                            @if ($kategori !== '')
                                <input type="hidden" name="kategori" value="{{ $kategori }}">
                            @endif
                            <button type="submit" aria-label="Cari berita">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <input type="search" name="q" value="{{ $pencarian }}" placeholder="Cari berita..." aria-label="Cari judul berita">
                        </form>
                        {{-- FILTER --}}
                        <div class="filter-group">
                            <a href="{{ route('berita', array_filter(['q' => $pencarian])) }}#daftar-berita"
                                class="filter-btn {{ $kategori === '' ? 'active' : '' }}">
                                Semua
                            </a>
                            @foreach ($kategoriCounts as $namaKategori => $total)
                                <a href="{{ route('berita', array_filter(['kategori' => $namaKategori, 'q' => $pencarian])) }}#daftar-berita"
                                    class="filter-btn {{ $kategori === $namaKategori ? 'active' : '' }}">
                                    {{ $namaKategori }}
                                    <span>{{ $total }}</span>
                                </a>
                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- GRID --}}
                <div class="news-grid">

                    {{-- CARD --}}
                    @foreach ($beritas as $berita)
                        <div class="news-card" data-category="{{ strtolower($berita->kategori) }}">

                            <div class="image-wrapper">

                                <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="{{ $berita->judul }}">

                                <span class="badge">
                                    {{ $berita->kategori }}
                                </span>

                            </div>

                            <div class="content">

                                <div class="meta">

                                    <span>
                                        <i class="fa-solid fa-calendar-days"></i>
                                        {{ $berita->tanggal_publish->format('d M Y') }}
                                    </span>

                                </div>

                                <h4>
                                    {{ $berita->judul }}
                                </h4>

                                <p>
                                    {{ Str::limit($berita->isi_berita, 90) }}
                                </p>

                                <a href="{{ route('detail-berita', $berita->slug) }}" class="read-more">

                                    Baca Selengkapnya
                                    <i class="fa-solid fa-arrow-right"></i>

                                </a>

                            </div>

                        </div>
                    @endforeach

                </div>

                @if ($beritas->isEmpty())
                    <div class="news-empty">
                        <i class="fa-regular fa-newspaper"></i>
                        <h4>Berita tidak ditemukan</h4>
                        <p>Coba gunakan kata kunci atau kategori lain.</p>
                    </div>
                @endif

                @if ($beritas->hasPages())
                    <div class="news-pagination">
                        {{ $beritas->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>
    </section>
    @include('components.footer')
@endsection
