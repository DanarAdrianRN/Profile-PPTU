@extends('layout.app')
@include('components.header')
@section('content')
    <section class="galeri">
        {{-- HERO --}}
        <div class="gallery-hero">
            <div class="hero-slider">
                <div class="hero-slide active">
                    <img src="{{ asset('assets/galerI.JPG') }}" alt="">
                    <div class="overlay">
                        <span>
                            Dokumentasi Kegiatan
                        </span>
                        <h1>
                            Galeri Yayasan Tarbiyatul 'Ulum
                        </h1>
                        <p>
                            Kumpulan momen kegiatan santri, pendidikan,
                            acara pesantren, dan aktivitas lainnya.
                        </p>
                    </div>
                </div>
                <div class="hero-slide">
                    <img src="{{ asset('assets/galeri2.jpg') }}" alt="">
                    <div class="overlay">
                        <span>
                            Aktivitas Santri
                        </span>
                        <h1>
                            Kehidupan Pesantren
                        </h1>
                        <p>
                            Dokumentasi kegiatan belajar, ibadah,
                            dan aktivitas harian santri.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- CONTENT --}}
        <div class="container">
            <div class="gallery-grid">
                {{-- ITEM --}}
                @foreach($galeris as $galeri)
                <div class="gallery-card"
                    data-toggle="modal"
                    data-target="#galleryModal{{$galeri->id}}">
                    <img src="{{ asset('storage/' . $galeri->thumbnail) }}"
                        alt="{{ $galeri->judul }}">
                    <div class="overlay">
                        <h3>{{ $galeri->judul }}</h3>
                        <div class="gallery-bot">
                            <span class="gallery-date">
                                <i class="fa-regular fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($galeri->tanggal)->translatedFormat('d F Y') }}
                            </span>
                            <div class="photo-count">
                                <i class="fa-regular fa-images"></i>
                                {{ $galeri->fotos->count() }} Foto
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        {{-- MODAL --}}
        @foreach($galeris as $galeri)
        <div class="modal fade"
            id="galleryModal{{$galeri->id}}"
            tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <h2 class="modal-title">
                        {{ $galeri->judul }}
                    </h2>
                    <div class="slider-track">

                        @foreach($galeri->fotos as $foto)
                        <div class="slide">
                            <img src="{{ asset('storage/' . $foto->gambar) }}"
                                alt="{{ $galeri->judul }}">
                        </div>
                        @endforeach
                    </div>
                    <div class="slider-control">
                        <button class="close-modal"
                            data-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>
    @push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // ambil semua modal gallery
            const galleryModals = document.querySelectorAll('.modal');

            galleryModals.forEach(modal => {

                const slides = modal.querySelectorAll('.slider-track .slide');

                if (slides.length === 0) return;

                let current = 0;

                function renderSlider() {

                    slides.forEach(slide => {
                        slide.classList.remove(
                            'active',
                            'left',
                            'right',
                            'hidden'
                        );
                    });

                    const total = slides.length;

                    // jika cuma 1 gambar
                    if (total === 1) {
                        slides[0].classList.add('active');
                        return;
                    }

                    const prev =
                        (current - 1 + total) % total;

                    const next =
                        (current + 1) % total;

                    // tengah
                    slides[current].classList.add('active');

                    // kiri
                    slides[prev].classList.add('left');

                    // kanan
                    slides[next].classList.add('right');

                    // hidden lainnya
                    slides.forEach((slide, index) => {
                        if (
                            index !== current &&
                            index !== prev &&
                            index !== next
                        ) {
                            slide.classList.add('hidden');
                        }
                    });
                }

                // klik gambar pindah slide
                slides.forEach((slide, index) => {
                    slide.addEventListener('click', () => {
                        current = index;
                        renderSlider();
                    });
                });

                // reset saat modal dibuka
                $(modal).on('shown.bs.modal', function () {
                    current = 0;
                    renderSlider();
                });

                renderSlider();
            });
        });
    </script>
    @endpush
    @include('components.footer')
@endsection
