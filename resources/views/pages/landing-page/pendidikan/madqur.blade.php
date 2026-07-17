@extends('layout.app')

@include('components.header')

@section('content')
    @include('components.hero', [
        'badge' => 'Jenjang Pendidikan Non Formal',
        'title' => 'Madrasah',
        'titlespan' => 'Al-Qur\'an',
        'subtitle' =>
            'Program pembinaan bacaan, hafalan, muraja\'ah, dan adab penghafal Al-Qur\'an untuk membentuk santri yang tartil, disiplin, dan istiqomah.',
    ])

    <section class="profile-madqur">
        <div class="container">
            <div class="profile-wrapper">
                <div class="school-image">
                    <img src="{{ asset('assets/madqur.png') }}" alt="Logo Madrasah Al-Qur'an">
                </div>
                <div class="school-content">
                    <span class="section-kicker">Tentang MADQUR</span>
                    <h2>Menguatkan Bacaan dan Hafalan dengan Pembinaan Terarah</h2>
                    <p>
                        MADQUR Darussholihin dirancang untuk memperbaiki kualitas bacaan santri
                        sekaligus membangun hafalan Al-Qur'an secara bertahap. Pembelajaran menekankan tahsin,
                        tajwid, setoran hafalan, muraja'ah, dan pembentukan adab terhadap Al-Qur'an.
                    </p>
                    <div class="school-info">
                        <div class="info-item">
                            <i class="fa-solid fa-book-quran"></i>
                            <span>Tahsin & Tajwid</span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-brain"></i>
                            <span>Setoran Hafalan</span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-rotate"></i>
                            <span>Muraja'ah Rutin</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="program-madqur">
        <div class="container">
            <div class="section-title">
                <span>Program Utama</span>
                <h2>Pembelajaran Madrasah Al-Qur'an</h2>
                <p>
                    Setiap program saling melengkapi agar santri tidak hanya banyak menghafal,
                    tetapi juga menjaga ketepatan bacaan dan kedisiplinan muraja'ah.
                </p>
            </div>

            <div class="program-list">
                <div class="program-item">
                    <div class="program-image">
                        <img src="{{ asset('assets/madqur1.jpeg') }}" alt="Pembelajaran tahsin Al-Qur'an">
                    </div>
                    <div class="program-content">
                        <span class="level">Tahsin Bacaan</span>
                        <h3>Memperbaiki Makharijul Huruf dan Tajwid</h3>
                        <p>
                            Santri dibimbing membaca Al-Qur'an dengan tartil, memperhatikan makhraj,
                            sifat huruf, panjang pendek bacaan, serta kaidah tajwid yang benar.
                        </p>
                        <div class="kitab-list">
                            <div class="kitab"><i class="fa-solid fa-check"></i><span>Makharijul Huruf</span></div>
                            <div class="kitab"><i class="fa-solid fa-check"></i><span>Tajwid Praktis</span></div>
                            <div class="kitab"><i class="fa-solid fa-check"></i><span>Talaqqi Bacaan</span></div>
                        </div>
                    </div>
                </div>

                <div class="program-item reverse">
                    <div class="program-image">
                        <img src="{{ asset('assets/madqur2.jpeg') }}" alt="Setoran hafalan santri">
                    </div>
                    <div class="program-content">
                        <span class="level">Tahfidz Bertahap</span>
                        <h3>Setoran Hafalan Sesuai Kemampuan Santri</h3>
                        <p>
                            Target hafalan dibuat realistis dan berkelanjutan, sehingga santri mampu
                            menambah hafalan baru tanpa meninggalkan hafalan lama.
                        </p>
                        <div class="kitab-list">
                            <div class="kitab"><i class="fa-solid fa-check"></i><span>Setoran Harian</span></div>
                            <div class="kitab"><i class="fa-solid fa-check"></i><span>Target Personal</span></div>
                            <div class="kitab"><i class="fa-solid fa-check"></i><span>Pendampingan Ustadz</span></div>
                        </div>
                    </div>
                </div>

                <div class="program-item">
                    <div class="program-image">
                        <img src="{{ asset('assets/madqur3.jpeg') }}" alt="Muraja'ah hafalan Al-Qur'an">
                    </div>
                    <div class="program-content">
                        <span class="level">Muraja'ah & Evaluasi</span>
                        <h3>Menjaga Hafalan agar Tetap Kuat</h3>
                        <p>
                            Hafalan santri dievaluasi melalui muraja'ah rutin, sima'an, dan catatan capaian
                            agar perkembangan belajar mudah dipantau.
                        </p>
                        <div class="kitab-list">
                            <div class="kitab"><i class="fa-solid fa-check"></i><span>Muraja'ah Rutin</span></div>
                            <div class="kitab"><i class="fa-solid fa-check"></i><span>Sima'an</span></div>
                            <div class="kitab"><i class="fa-solid fa-check"></i><span>Evaluasi Capaian</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="target-madqur">
        <div class="container">
            <div class="section-title">
                <span>Capaian Santri</span>
                <h2>Target Pembinaan</h2>
                <p>
                    MADQUR menyeimbangkan kualitas bacaan, kekuatan hafalan, dan akhlak santri terhadap Al-Qur'an.
                </p>
            </div>

            <div class="target-grid">
                <div class="target-card">
                    <i class="fa-solid fa-volume-high"></i>
                    <h3>Bacaan Tartil</h3>
                    <p>Santri mampu membaca dengan jelas, tenang, dan sesuai kaidah tajwid.</p>
                </div>
                <div class="target-card">
                    <i class="fa-solid fa-layer-group"></i>
                    <h3>Hafalan</h3>
                    <p>Santri mampu menghafal Juz 30 beserta surat-surat pilihan, yaitu Yasin, Al-Waqi'ah, Al-Mulk, dan Al-Kahfi.</p>
                </div>
                <div class="target-card">
                    <i class="fa-solid fa-user-check"></i>
                    <h3>Disiplin Muraja'ah</h3>
                    <p>Santri terbiasa mengulang hafalan sebagai bagian dari rutinitas harian.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <h2>Bersama MADQUR, Hafalan Tumbuh dengan Bacaan yang Terjaga</h2>
        <p>Daftarkan santri untuk mengikuti pembinaan Al-Qur'an yang terarah dan berkelanjutan.</p>
        <div class="cta-button">
            <a href="{{ route('informasi-pendaftaran') }}" class="btn">Daftar Sekarang</a>
            <a href="{{ route('guru') }}" class="btn fadee">Hubungi Kami</a>
        </div>
    </section>

    @include('components.footer')
@endsection
