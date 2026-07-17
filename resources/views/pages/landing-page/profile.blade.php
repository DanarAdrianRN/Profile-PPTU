@extends('layout.app')

@include('components.header')

@section('content')
    @include('components.hero', [
        'badge' => 'Pendidikan Islam Berkualitas',
        'title' => 'Pondok Pesantren',
        'titlespan' => 'Tarbiyatul Ulum',
        'subtitle' =>
            'Membentuk generasi yang beriman, berilmu, dan berakhlak mulia melalui pendidikan Islam yang berkualitas, terpadu, serta didukung lingkungan belajar yang religius dan inspiratif.',
    ])

    <section class="sejarah">
        <div class="container">
            <div class="sejarah-content">
                <div class="text">
                    <h2>Sejarah Singkat</h2>
                    <p>
                        Pondok Pesantren Tarbiyatul 'Ulum Sumursongo (PPTU) didirikan pada
                        tanggal <strong>20 Dzulqa'dah 1400 H</strong> bertepatan dengan
                        <strong>29 September 1980 M</strong>. Sejak berdiri, pondok
                        pesantren ini berkomitmen menyelenggarakan pendidikan yang
                        memadukan nilai-nilai keislaman dengan pembinaan karakter,
                        sehingga mampu mencetak generasi yang unggul, berakhlak mulia,
                        serta memiliki wawasan kebangsaan. Berbagai pengembangan di
                        bidang pendidikan, manajemen, dan inovasi pembelajaran terus
                        dilakukan untuk mendukung pelaksanaan fungsi pondok pesantren
                        sebagai lembaga pendidikan, dakwah, dan pemberdayaan masyarakat
                        sesuai amanat Undang-Undang Nomor 18 Tahun 2019 tentang
                        Pesantren.
                    </p>
                </div>
                <div class="image">
                    <img src="{{ asset('assets/pptu.png') }}" alt="Sejarah Pondok Pesantren">
                </div>
            </div>
            <div class="sejarah-content reverse">
                <div class="image">
                    <img src="{{ asset('assets/masayikh.png') }}" alt="Masyayikh Pondok">
                </div>
                <div class="text">
                    <h2>Masyayikh Pondok Pesantren</h2>

                    <p>
                        Perjalanan dan perkembangan Pondok Pesantren Tarbiyatul 'Ulum
                        Sumursongo tidak terlepas dari perjuangan serta pengabdian para
                        Masyayikh yang telah meletakkan dasar-dasar pendidikan dan
                        nilai-nilai keislaman di lingkungan pesantren. Keteladanan,
                        keikhlasan, dan dedikasi mereka menjadi warisan berharga yang
                        terus dijaga dalam proses pendidikan hingga saat ini. Estafet
                        kepemimpinan tersebut kini dilanjutkan oleh
                        <strong>KH. Khoirudin Yusuf, S.Pd.I</strong> selaku Pengasuh
                        Pondok Pesantren sekaligus Ketua Yayasan Tarbiyatul 'Ulum
                        Sumursongo.
                    </p>
                    <div class="masyayikh-list">
                        <div class="item">
                            <span>01</span>
                            KH. Sholeh Rofi'i <small>(Alm.)</small>
                        </div>
                        <div class="item">
                            <span>02</span>
                            KH. Dimyati Rofi'i <small>(Alm.)</small>
                        </div>
                        <div class="item">
                            <span>03</span>
                            KH. Muhammad Rofi'i <small>(Alm.)</small>
                        </div>
                        <div class="item">
                            <span>04</span>
                            Dr. KH. Syaifuddin Muhammad <small>(Alm.)</small>
                        </div>
                        <div class="item active">
                            <span>05</span>
                            KH. Khoirudin Yusuf, S.Pd.I
                            <small>Pengasuh & Ketua Yayasan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 
    {{-- <section class="visi-misi">
        <div class="container">
            <h2 class="section-title">Visi & Misi</h2>
            <div class="vm-wrapper">
                <div class="card vm-card">
                    <div class="card-header">
                        <div class="icon">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <h3>Visi</h3>
                    </div>
                    <p>
                        Menjadi lembaga pendidikan Islam yang unggul dalam mencetak generasi
                        berakhlakul karimah, berilmu, mandiri, dan mampu bersaing di era global
                        dengan tetap berlandaskan nilai-nilai Ahlussunnah wal Jamaah.
                    </p>
                </div>
                <div class="card vm-card">
                    <div class="card-header">
                        <div class="icon">
                            <i class="fa-solid fa-list"></i>
                        </div>
                        <h3>Misi</h3>
                    </div>
                    <ul class="misi-list">
                        <li>Menyelenggarakan pendidikan Islam yang berkualitas</li>
                        <li>Membentuk karakter santri yang berakhlakul karimah</li>
                        <li>Mengembangkan potensi akademik dan non-akademik</li>
                        <li>Membekali santri dengan keterampilan masa depan</li>
                        <li>Menciptakan lingkungan belajar yang islami dan kondusif</li>
                    </ul>
                </div>
            </div>
        </div>
    </section> --}}
    <section class="core-values">
        <div class="container">
            <h2 class="section-title">
                Nilai-Nilai Inti
            </h2>
            <p class="section-subtitle">
                Nilai-nilai yang menjadi landasan dalam membentuk generasi yang berilmu, berakhlak, dan bermanfaat bagi masyarakat.
            </p>
            <div class="values-grid">
                <div class="value-card">
                    <div class="icon">
                        <i class="fa-solid fa-book-quran"></i>
                    </div>
                    <h3>Keislaman</h3>
                    <p>
                        Menanamkan nilai-nilai Islam dalam setiap proses pendidikan, ibadah, dan kehidupan sehari-hari.
                    </p>
                </div>
                <div class="value-card">
                    <div class="icon">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <h3>Akhlakul Karimah</h3>
                    <p>
                        Membentuk pribadi yang jujur, amanah, disiplin, dan bertanggung jawab dalam setiap tindakan.
                    </p>
                </div>
                <div class="value-card">
                    <div class="icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3>Keilmuan</h3>
                    <p>
                        Mengembangkan ilmu agama dan ilmu pengetahuan secara seimbang sebagai bekal kehidupan.
                    </p>
                </div>
                <div class="value-card">
                    <div class="icon">
                        <i class="fa-solid fa-handshake-angle"></i>
                    </div>
                    <h3>Sopan Santun</h3>
                    <p>
                        Membiasakan adab kepada Allah, guru, orang tua, dan sesama sebagai bagian dari kehidupan santri.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="achievements">
        <div class="container">
            <h2 class="section-title">Kehidupan Pesantren</h2>
            <p class="section-subtitle">
                Suasana pendidikan yang membentuk ilmu, akhlak, dan karakter santri dalam kehidupan sehari-hari.
            </p>
            <div class="swiper achievements-slider">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div class="achievement-card primary">
                            <div class="icon">
                                <i class="fa-solid fa-house"></i>
                            </div>
                            <h3>Kehidupan Berasrama</h3>
                            <p>
                                Seluruh santri tinggal di lingkungan pondok sehingga terbiasa hidup mandiri,
                                disiplin, menjaga kebersihan, serta membangun kebersamaan dengan sesama.
                            </p>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="achievement-card yellow">
                            <div class="icon">
                                <i class="fa-solid fa-book-quran"></i>
                            </div>
                            <h3>Pembelajaran Kitab Kuning</h3>
                            <p>
                                Santri mempelajari kitab-kitab klasik sebagai dasar memahami ilmu agama,
                                fiqih, tauhid, akhlak, dan bahasa Arab sesuai tradisi pesantren.
                            </p>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div class="achievement-card primary">
                            <div class="icon">
                                <i class="fa-solid fa-mosque"></i>
                            </div>
                            <h3>Pembiasaan Ibadah</h3>
                            <p>
                                Kegiatan harian dibiasakan dengan shalat berjamaah, membaca Al-Qur'an,
                                dzikir, serta berbagai amalan untuk membentuk karakter Islami.
                            </p>
                        </div>
                    </div>
                    <!-- Slide 4 -->
                    <div class="swiper-slide">
                        <div class="achievement-card yellow">
                            <div class="icon">
                                <i class="fa-solid fa-school"></i>
                            </div>
                            <h3>Pendidikan Terpadu</h3>
                            <p>
                                Pendidikan formal dipadukan dengan Madrasah Diniyah, Madrasah Al-Qur'an,
                                dan pembinaan kepesantrenan dalam satu lingkungan belajar.
                            </p>
                        </div>
                    </div>
                    <!-- Slide 5 -->
                    <div class="swiper-slide">
                        <div class="achievement-card primary">
                            <div class="icon">
                                <i class="fa-solid fa-seedling"></i>
                            </div>
                            <h3>Pembinaan Karakter</h3>
                            <p>
                                Kehidupan di pesantren membentuk pribadi yang mandiri, disiplin,
                                bertanggung jawab, serta memiliki kepedulian terhadap sesama.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="motto">
        <div class="container">
            <div class="motto-box">
                <span class="arab">
                ٱلْمُحَافَظَةُ عَلَى ٱلْقَدِيمِ ٱلصَّالِحِ وَٱلْأَخْذُ بِٱلْجَدِيدِ ٱلْأَصْلَحِ                
                </span>
                <h3>
                    “Memelihara tradisi lama yang baik dan mengambil hal-hal baru yang lebih baik.”
                </h3>
            </div>
        </div>
    </section>
    <section class="cta">
        <h2>Siapkan Masa Depan Anak Anda Bersama Kami</h2>
        <p>Daftar sekarang dan berikan pendidikan terbaik untuk buah hati Anda</p>
        <div class="cta-button">
            <a href="{{ route('informasi-pendaftaran') }}" class="btn">Daftar Sekarang</a>
            <a href="{{ route('profile') }}" class="btn fadee">Hubungi Kami</a>
        </div>
    </section>

    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const swiper = new Swiper(".achievements-slider", {
                    loop: true,
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: 1,
                            spaceBetween: 14,
                        },
                        576: {
                            slidesPerView: 2,
                            spaceBetween: 18,
                        },
                        992: {
                            slidesPerView: 3,
                            spaceBetween: 25,
                        },
                    },
                });

                console.log("Swiper aktif:", swiper);
            });
        </script>
    @endpush
    @include('components.footer')
@endsection
