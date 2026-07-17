@extends('layout.app')
@include('components.header')
@section('content')
    <section class="detail-berita">
        {{-- HERO --}}
        <div class="detail-hero">
            <img src="{{ asset('storage/'. $berita->thumbnail) }}" alt="{{ $berita->judul }}">
            <div class="overlay"></div>
            <div class="hero-content container">
                <span class="badge-news">
                    {{ $berita->kategori }}
                </span>
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
                <h1>
                    {{ $berita->judul }}
                </h1>
            </div>
        </div>
        {{-- CONTENT --}}
        <div class="container">
            <div class="detail-wrapper">
                {{-- MAIN CONTENT --}}
                <div class="detail-content">
                    {{-- IMAGE --}}
                    <div class="thumbnail">
                        <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="{{ $berita->judul }}">
                    </div>
                    {{-- ARTICLE --}}
                    @php
                        $paragraphs = array_filter(
                            preg_split('/\r\n|\r|\n/', $berita->isi_berita)
                        );

                        $half = ceil(count($paragraphs) / 2);
                    @endphp
                    <div class="article-content">

                        {{-- PARAGRAF ATAS --}}
                        @foreach (array_slice($paragraphs, 0, $half) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach

                        {{-- BLOCKQUOTE --}}
                        @if ($berita->blockquote)
                            <div class="quote-box">
                                <i class="fa-solid fa-quote-left"></i>
                                <p>
                                    {{ $berita->blockquote }}
                                </p>
                            </div>
                        @endif

                        {{-- GAMBAR DETAIL 1 --}}
                        @if ($berita->gambar_detail_1)
                            <div class="article-image">
                                <img src="{{ asset('storage/' . $berita->gambar_detail_1) }}"
                                    alt="{{ $berita->judul }}">
                            </div>
                        @endif

                        {{-- PARAGRAF BAWAH --}}
                        @foreach (array_slice($paragraphs, $half) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach

                        {{-- GAMBAR DETAIL 2 --}}
                        @if ($berita->gambar_detail_2)
                            <div class="article-image">
                                <img src="{{ asset('storage/' . $berita->gambar_detail_2) }}"
                                    alt="{{ $berita->judul }}">
                            </div>
                        @endif

                    </div>
                    {{-- SHARE --}}
                    <div class="share-box">
                        <span>Bagikan Berita :</span>
                        <div class="share-btns">
                            <a href="#">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="#">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
                {{-- SIDEBAR --}}
                <aside class="sidebar-berita">
                    {{-- PENULIS --}}
                    <div class="sidebar-card">
                        <h4>
                            Penulis
                        </h4>
                        <div class="author-box">
                            <img src="{{ asset('assets/pptu.png') }}" alt="">
                            <div>
                                <h5>{{ $berita->penulis }}</h5>
                            </div>
                        </div>
                    </div>
                    {{-- BERITA LAIN --}}
                    <div class="sidebar-card">
                        <h4>
                            Berita Lainnya
                        </h4>
                        @foreach ($relatedBeritas as $item)
                        <a href="{{ route('detail-berita', $item->slug) }}"
                            class="related-news">
                            <img src="{{ asset('storage/' . $item->thumbnail) }}"
                                alt="{{ $item->judul }}">
                            <div class="related-content">
                                <span>
                                    {{ $item->tanggal_publish->format('d M Y') }}
                                </span>
                                <h5>
                                    {{ Str::limit($item->judul, 45) }}
                                </h5>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    {{-- KATEGORI --}}
                    <div class="sidebar-card">
                        <h4>
                            Kategori
                        </h4>
                        <div class="category-list">
                            <a href="#">
                                Pendaftaran
                                <span>{{ $kategoriCounts['Pendaftaran'] ?? 0 }}</span>
                            </a>
                            <a href="#">
                                Prestasi
                                <span>{{ $kategoriCounts['Prestasi'] ?? 0 }}</span>
                            </a>
                            <a href="#">
                                Pengumuman
                                <span>{{ $kategoriCounts['Pengumuman'] ?? 0 }}</span>
                            </a>
                            <a href="#">
                                Kegiatan
                                <span>{{ $kategoriCounts['Kegiatan'] ?? 0 }}</span>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    @include('components.footer')
@endsection
