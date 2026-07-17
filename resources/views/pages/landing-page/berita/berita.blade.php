@extends('layout.app')

@include('components.header')

@section('content')
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

                            <span>
                                <i class="fa-solid fa-user"></i>
                                {{ $featuredBerita->penulis }}
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
            <div class="news-wrapper">
                {{-- FILTER --}}
                <div class="news-filter">
                    <div class="filter-title">
                        <h3>Berita & Pengumuman</h3>
                        <p>
                            Cari dan filter informasi terbaru yayasan
                        </p>
                    </div>
                    <div class="filter-action">
                        {{-- SEARCH --}}
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="searchInput" placeholder="Cari berita...">
                        </div>
                        {{-- FILTER --}}
                        <div class="filter-group">
                            <button class="filter-btn active" data-filter="all">
                                Semua
                            </button>
                            <button class="filter-btn" data-filter="prestasi">
                                Prestasi
                            </button>
                            <button class="filter-btn" data-filter="pengumuman">
                                Pengumuman
                            </button>
                            <button class="filter-btn" data-filter="kegiatan">
                                Kegiatan
                            </button>

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

                                    <span>
                                        <i class="fa-solid fa-user"></i>
                                        {{ $berita->penulis }}
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

            </div>
        </div>
    </section>
    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const filterBtns = document.querySelectorAll(".filter-btn");
                const newsCards = document.querySelectorAll(".news-card");
                const searchInput = document.getElementById("searchInput");

                // FILTER
                filterBtns.forEach(btn => {
                    btn.addEventListener("click", function() {
                        filterBtns.forEach(b => b.classList.remove("active"));
                        this.classList.add("active");
                        const filter = this.dataset.filter;
                        newsCards.forEach(card => {
                            if (filter === "all" || card.dataset.category === filter) {
                                card.style.display = "block";
                            } else {
                                card.style.display = "none";
                            }
                        });
                    });
                });
                // SEARCH
                searchInput.addEventListener("keyup", function() {
                    const keyword = this.value.toLowerCase();
                    newsCards.forEach(card => {
                        const title = card.querySelector("h4")
                            .innerText.toLowerCase();
                        if (title.includes(keyword)) {
                            card.style.display = "block";
                        } else {
                            card.style.display = "none";
                        }
                    });
                });
            });
        </script>
    @endpush
    @include('components.footer')
@endsection
