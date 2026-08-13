@extends('layout.app')
@section('content')
    @include('components.header')
    <section class="galeri">
        {{-- HERO --}}
        <div class="gallery-hero">
            <div class="hero-slider">
                <div class="hero-slide active">
                    <img src="{{ asset('assets/galeri.JPG') }}" alt="">
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
            </div>
        </div>
        {{-- CONTENT --}}
        <div class="container">
            <div class="gallery-grid">

                @foreach ($galeris as $galeri)
                    <div class="gallery-card"
                        data-toggle="modal"
                        data-target="#galleryModal{{ $galeri->id }}">

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

            {{-- PAGINATION --}}
            @if ($galeris->hasPages())
                <div class="gallery-pagination">
                    {{ $galeris->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
        {{-- MODAL --}}
        @foreach ($galeris as $galeri)
            <div class="modal fade" id="galleryModal{{ $galeri->id }}" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <h2 class="modal-title">
                            {{ $galeri->judul }}
                        </h2>
                        <div class="slider-track">

                            @foreach ($galeri->fotos as $foto)
                                <div class="slide">
                                    <img src="{{ asset('storage/' . $foto->gambar) }}" alt="{{ $galeri->judul }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="slider-swipe-hint">
                            <i class="fa-solid fa-arrows-left-right"></i>
                            <span>Geser untuk melihat foto lainnya</span>
                        </div>
                        <div class="slider-control">
                            <button class="close-modal" data-dismiss="modal">
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
            document.addEventListener("DOMContentLoaded", function() {

                const galleryModals = document.querySelectorAll('.modal');

                galleryModals.forEach(modal => {

                    const sliderTrack = modal.querySelector('.slider-track');
                    const slides = modal.querySelectorAll('.slider-track .slide');

                    if (!sliderTrack || slides.length === 0) return;

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

                        // Jika hanya ada 1 gambar
                        if (total === 1) {
                            slides[0].classList.add('active');
                            return;
                        }

                        const prev = (current - 1 + total) % total;
                        const next = (current + 1) % total;

                        // Gambar utama
                        slides[current].classList.add('active');

                        // Gambar sebelumnya
                        slides[prev].classList.add('left');

                        // Gambar berikutnya
                        slides[next].classList.add('right');

                        // Sembunyikan gambar lainnya
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

                    // Klik gambar
                    slides.forEach((slide, index) => {
                        slide.addEventListener('click', () => {
                            current = index;
                            renderSlider();
                        });
                    });

                    // ==========================
                    // SWIPE / GESER
                    // ==========================

                    let startX = 0;
                    let startY = 0;
                    let isDragging = false;

                    sliderTrack.addEventListener('touchstart', function(e) {

                        if (e.touches.length !== 1) return;

                        startX = e.touches[0].clientX;
                        startY = e.touches[0].clientY;
                        isDragging = true;

                    }, {
                        passive: true
                    });


                    sliderTrack.addEventListener('touchend', function(e) {

                        if (!isDragging) return;

                        isDragging = false;

                        const endX = e.changedTouches[0].clientX;
                        const endY = e.changedTouches[0].clientY;

                        const diffX = endX - startX;
                        const diffY = endY - startY;

                        // Abaikan jika gerakannya lebih dominan vertikal
                        if (Math.abs(diffY) > Math.abs(diffX)) {
                            return;
                        }

                        // Minimal jarak swipe
                        const swipeThreshold = 50;

                        if (Math.abs(diffX) < swipeThreshold) {
                            return;
                        }

                        // Swipe ke kiri
                        if (diffX < 0) {
                            current = (current + 1) % slides.length;
                        }

                        // Swipe ke kanan
                        else {
                            current = (current - 1 + slides.length) % slides.length;
                        }

                        renderSlider();

                    }, {
                        passive: true
                    });


                    // ==========================
                    // RESET SAAT MODAL DIBUKA
                    // ==========================

                    $(modal).on('shown.bs.modal', function() {
                        current = 0;
                        renderSlider();
                    });


                    // Render pertama
                    renderSlider();

                });

            });
        </script>
    @endpush
    @include('components.footer')
@endsection
