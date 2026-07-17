@extends('layout.app')
@include('components.header')
@section('content')
    <section class="hero">
        <div class="hero-content">
            <h1>Pondok Pesantren Tarbiyatul Ulum</h1>
            <p>
                Mencetak generasi islami yang unggul, berilmu, dan berakhlak mulia
                melalui pendidikan terpadu berbasis Al-Qur'an dan Sunnah.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('virtual-tour')}}" class="btn">Jelajahi Pondok <i class="fa-solid fa-arrow-right-long"></i></a>
                <a href="{{ route('profile') }}" class="btn fadee">Kenali Kami <i class="fa-solid fa-arrow-right-long"></i></a>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="container">
            <div class="stat-item">
                <h2>8+</h2>
                <p>Ekstrakulikuler</p>
            </div>

            <div class="stat-item">
                <h2>850+</h2>
                <p>Santri Mukim</p>
            </div>

            <div class="stat-item">
                <h2>80+</h2>
                <p>Santri Kalong</p>
            </div>

            <div class="stat-item">
                <h2>1500+</h2>
                <p>Alumni</p>
            </div>
        </div>
    </section>

    <section class="program">
        <div class="container">
            <h2 class="section-title">Program Pendidikan</h2>
            <p class="section-subtitle">
                Pilih jenjang pendidikan yang sesuai dengan kebutuhan putra-putri Anda.
            </p>

            <div class="program-grid">

                <!-- TPQ -->
                <a href="{{ route('pendidikan.tpq') }}" class="card">
                    <div class="card-body">
                        <img src="{{ asset('assets/tpq.jpg') }}" alt="Logo TPQ" class="logo">
                        <h3>Taman Pendidikan Al-Qur'an</h3>
                        <span class="link">
                            Lihat Program →
                        </span>
                    </div>
                </a>

                <!-- Madrasah Al-Qur'an -->
                <a href="{{ route('pendidikan.madqur') }}" class="card">
                    <div class="card-body">
                        <img src="{{ asset('assets/madqur.png') }}" alt="Logo Madrasah Al-Qur'an" class="logo">
                        <h3>Madrasah Al-Qur'an</h3>
                        <span class="link">
                            Lihat Program →
                        </span>
                    </div>
                </a>

                <!-- Madrasah Diniyah -->
                <a href="{{ route('pendidikan.madin') }}" class="card">
                    <div class="card-body">
                        <img src="{{ asset('assets/madin.png') }}" alt="Logo Madrasah Diniyah" class="logo">
                        <h3>Madrasah Diniyah</h3>
                        <span class="link">
                            Lihat Program →
                        </span>
                    </div>
                </a>

                <!-- SMP -->
                <a href="{{ route('pendidikan.smp') }}" class="card">
                    <div class="card-body">
                        <img src="{{ asset('assets/smp.png') }}" alt="Logo SMP" class="logo">
                        <h3>SMP Ma'arif</h3>
                        <span class="link">
                            Lihat Program →
                        </span>
                    </div>
                </a>

                <!-- SMK -->
                <a href="{{ route('pendidikan.smk') }}" class="card">
                    <div class="card-body">
                        <img src="{{ asset('assets/smk.png') }}" alt="Logo SMK" class="logo">
                        <h3>SMK Ma'arif</h3>
                        <span class="link">
                            Lihat Program →
                        </span>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <section class="why">
        <div class="container">
            <h2 class="section-title">Keunggulan Pondok Pesantren Tarbiyatul 'Ulum</h2>
            <p class="section-subtitle">
                Pendidikan terpadu yang mengutamakan ilmu, akhlak, dan pengembangan potensi santri.
            </p>

            <div class="why-grid">

                <!-- Pendidikan -->
                <div class="why-card">
                    <div class="icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <h3>Pendidikan Formal & Keagamaan</h3>
                    <p>
                        Menyelenggarakan pendidikan formal dan keagamaan secara terpadu
                        mulai dari TPQ, Madrasah Al-Qur'an, Madrasah Diniyah,
                        SMP Islam Terpadu, hingga SMK Islam Terpadu.
                    </p>
                </div>

                <!-- Pembiasaan -->
                <div class="why-card">
                    <div class="icon">
                        <i class="fas fa-mosque"></i>
                    </div>
                    <h3>Pembiasaan Ibadah & Akhlak</h3>
                    <p>
                        Santri dibimbing melalui pembiasaan shalat berjamaah,
                        membaca Al-Qur'an, dzikir, serta pembinaan akhlakul karimah
                        dalam kehidupan sehari-hari.
                    </p>
                </div>

                <!-- Kegiatan -->
                <div class="why-card">
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>Kegiatan Santri Terstruktur</h3>
                    <p>
                        Memiliki kegiatan harian, mingguan, bulanan, hingga tahunan
                        yang dirancang untuk membentuk kedisiplinan, tanggung jawab,
                        dan karakter santri.
                    </p>
                </div>

                <!-- Ekstrakurikuler -->
                <div class="why-card">
                    <div class="icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h3>Ekstrakurikuler Beragam</h3>
                    <p>
                        Santri dapat mengembangkan minat dan bakat melalui Hadrah,
                        Drumband, Kaligrafi, Tilawatil Qur'an, Pramuka, Futsal,
                        Leadership, serta berbagai kegiatan lainnya.
                    </p>
                </div>

                <!-- Lingkungan -->
                <div class="why-card">
                    <div class="icon">
                        <i class="fas fa-building-columns"></i>
                    </div>
                    <h3>Lingkungan Belajar Terpadu</h3>
                    <p>
                        Seluruh jenjang pendidikan berada dalam satu kawasan yayasan
                        sehingga proses pembelajaran, pembinaan, dan pengawasan
                        santri berlangsung secara berkesinambungan.
                    </p>
                </div>

                <!-- Bekal -->
                <div class="why-card">
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>Bekal Ilmu & Keterampilan</h3>
                    <p>
                        Mengintegrasikan pendidikan agama, ilmu pengetahuan umum,
                        dan keterampilan sesuai jenjang pendidikan untuk membentuk
                        lulusan yang berilmu, berakhlak, dan siap mengabdi kepada masyarakat.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section class="what">
        <div class="container">
            <h2 class="section-title">Apa Kata Mereka?</h2>
            <p class="section-subtitle">
                Testimoni dari santri, wali santri, dan alumni Pondok Pesantren Tarbiyatul Ulum
            </p>
            <div class="swiper what-slider">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="what-card">
                            <div class="stars">★★★★★</div>
                            <p>
                                "Belajar di sini sangat menyenangkan dan lingkungan pesantrennya sangat nyaman."
                            </p>
                            <div class="user">
                                <img src="{{ asset('assets/pp.jpg') }}" alt="">
                                <div>
                                    <h4>Dukha Fahri</h4>
                                    <span>Santri</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="what-card">
                            <div class="stars">★★★★★</div>
                            <p>
                                "Guru-guru membimbing dengan sabar sehingga anak menjadi lebih disiplin."
                            </p>
                            <div class="user">
                                <img src="{{ asset('assets/pp.jpg') }}" alt="">
                                <div>
                                    <h4>Munirul Ikhwan</h4>
                                    <span>Alumni</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="what-card">
                            <div class="stars">★★★★★</div>
                            <p>
                                "Lingkungan belajar nyaman dan pendidikan agama serta umum berjalan seimbang."
                            </p>
                            <div class="user">
                                <img src="{{ asset('assets/pp.jpg') }}" alt="">
                                <div>
                                    <h4>Siti Munawaroh</h4>
                                    <span>Wali Santri</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="what-card">
                            <div class="stars">★★★★★</div>
                            <p>
                                "Program pendidikan membuat saya lebih percaya diri dan memiliki bekal ilmu agama."
                            </p>
                            <div class="user">
                                <img src="{{ asset('assets/pp.jpg') }}" alt="">
                                <div>
                                    <h4>Muhammad Riski</h4>
                                    <span>Alumni</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="what-card">
                            <div class="stars">★★★★★</div>
                            <p>
                                "Pesantren ini menjadi tempat terbaik untuk membentuk akhlak dan kedisiplinan anak."
                            </p>
                            <div class="user">
                                <img src="{{ asset('assets/pp.jpg') }}" alt="">
                                <div>
                                    <h4>Abdullah</h4>
                                    <span>Wali Santri</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <h2>Siapkan Masa Depan Anak Anda Bersama Kami</h2>
        <p>Daftar sekarang dan berikan pendidikan terbaik untuk buah hati Anda</p>
        <div class="cta-button">
            <a href="{{ route('informasi-pendaftaran') }}" class="btn">Daftar Sekarang</a>
            <a href="{{ route('profile') }}" class="btn fadee">Bagikan Informasi</a>
        </div>
    </section>
    @include('components.footer')
        @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", function () {

                new Swiper(".what-slider", {
                    loop: true,
                    spaceBetween: 25,

                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                    },

                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },

                    breakpoints: {
                        0: {
                            slidesPerView: 1,
                        },
                        768: {
                            slidesPerView: 2,
                        },
                        992: {
                            slidesPerView: 3,
                        },
                    },
                });

            });
        </script>
    @endpush
@endsection
