@extends('layout.app')

@include('components.header')

@section('content')
    @include('components.hero', [
        'badge' => 'Jenjang Pendidikan Non Formal',
        'title' => 'Madrasah',
        'titlespan' => 'Diniyah',
        'subtitle' =>
            'Program pendidikan keagamaan yang membekali santri dengan ilmu agama, pembelajaran kitab, bahasa Arab, serta pembinaan akhlakul karimah.',
    ])

    <section class="profile-madin">
        <div class="container">
            <div class="profile-wrapper">

                <div class="school-image">
                    <img src="{{ asset('assets/madin.png') }}" alt="Logo Madrasah Diniyah">
                </div>

                <div class="school-content">
                    <span class="section-kicker">Tentang MADIN</span>

                    <h2>
                        Membentuk Generasi Berilmu, Berakhlak, dan Memahami Ajaran Islam
                    </h2>

                    <p>
                        Madrasah Diniyah Darus Sholihin merupakan lembaga pendidikan
                        keagamaan yang berfokus pada pendalaman ilmu-ilmu Islam melalui
                        pembelajaran kitab, bahasa Arab, serta pembinaan akhlakul karimah.
                        Kurikulum disusun secara bertahap mulai dari jenjang I'dadiyah,
                        Ula, hingga Wustho agar santri memiliki pemahaman agama yang
                        kuat dan mampu mengamalkannya dalam kehidupan sehari-hari.
                    </p>

                    <div class="school-info">

                        <div class="info-item">
                            <i class="fa-solid fa-book-open-reader"></i>
                            <span>Kajian Kitab</span>
                        </div>

                        <div class="info-item">
                            <i class="fa-solid fa-language"></i>
                            <span>Bahasa Arab</span>
                        </div>

                        <div class="info-item">
                            <i class="fa-solid fa-mosque"></i>
                            <span>Pembinaan Akhlak</span>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>

    <section class="program-madin">
        <div class="container">
            <div class="section-title">
                <span>Jenjang Pendidikan</span>
                <h2>
                    Program Madrasah Diniyah
                </h2>
                <p>
                    Pembelajaran disusun bertahap mulai dasar hingga lanjutan
                    agar santri mampu memahami ilmu agama secara mendalam.
                </p>
            </div>
            <div class="program-list">
                <div class="program-item reverse">
                    <div class="program-image">
                        <img src="{{ asset('assets/madin1.JPG') }}" alt="Program I'dadiyah">
                    </div>
                    <div class="program-content">
                        <span class="level">
                            Program I'dadiyah
                        </span>

                        <h3>
                            Dasar-Dasar Ilmu Keislaman
                        </h3>

                        <p>
                            Pembelajaran awal yang membekali santri dengan dasar-dasar ilmu agama,
                            pembiasaan ibadah, serta pengenalan ilmu alat dan bahasa Arab sebagai
                            bekal untuk mempelajari kitab kuning pada jenjang berikutnya.
                        </p>

                        <div class="kitab-list">

                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Ilmu Alat</span>
                            </div>

                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Tajwid</span>
                            </div>

                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Akhlaq</span>
                            </div>

                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Fiqih</span>
                            </div>

                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Tauhid</span>
                            </div>

                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Kitabah</span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="program-item">
                    <div class="program-image">
                        <img src="{{ asset('assets/madin2.JPG') }}" alt="Program Ula">
                    </div>
                    <div class="program-content">
                        <span class="level">
                            Program Ula
                        </span>
                        <h3>
                            Pendalaman Kitab Dasar dan Ilmu Keislaman
                        </h3>
                        <p>
                            Pada jenjang Ula, santri mulai memperdalam kajian kitab-kitab dasar,
                            memperkuat pemahaman fiqih, tauhid, hadits, serta ilmu alat sebagai
                            bekal untuk memahami kitab-kitab yang lebih tinggi pada jenjang Wustho.
                        </p>
                        <div class="kitab-list">
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Fiqih</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Tauhid</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Hadist</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Tarikh</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Aswaja</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Ilmu Alat</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="program-item reverse">
                    <div class="program-image">
                        <img src="{{ asset('assets/madin3.jpeg') }}" alt="Program Wustho">
                    </div>
                    <div class="program-content">
                        <span class="level">
                            Program Wustho
                        </span>
                        <h3>
                            Pendalaman Kitab Klasik dan Kajian Keislaman
                        </h3>
                        <p>
                            Jenjang lanjutan Madrasah Diniyah yang berfokus pada pendalaman
                            kitab-kitab klasik, penguatan analisis hukum Islam, serta
                            penguasaan ilmu alat agar santri mampu memahami, mengkaji,
                            dan mengamalkan ilmu keislaman secara lebih mendalam.
                        </p>
                        <div class="kitab-list">
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Tafsir Jalalain</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Fathul Qarib</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Kaidah Fiqih</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Faraid</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Hadist</span>
                            </div>
                            <div class="kitab">
                                <i class="fa-solid fa-book"></i>
                                <span>Ilmu Alat</span>
                            </div>
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
                <h2>Target Kompetensi Santri</h2>
                <p>
                    Madrasah Diniyah membekali santri dengan kemampuan membaca kitab,
    memahami ilmu agama, menguasai ilmu alat, serta membentuk akhlakul karimah.
                </p>
            </div>
            <div class="target-grid">
                <div class="target-card">
                    <i class="fa-solid fa-book-open-reader"></i>
                    <h3>Membaca Kitab Arab</h3>
                    <p>
                        Santri mampu membaca, memahami, dan mengartikan teks Arab,
                        baik yang berharakat maupun tanpa harakat (kitab gundul),
                        sesuai dengan jenjang pembelajaran.
                    </p>
                </div>
                <div class="target-card">
                    <i class="fa-solid fa-language"></i>
                    <h3>Penguasaan Ilmu Alat</h3>
                    <p>
                        Santri menguasai dasar-dasar nahwu dan sharaf sebagai bekal
                        memahami kitab-kitab keislaman secara mandiri.
                    </p>
                </div>
                <div class="target-card">
                    <i class="fa-solid fa-mosque"></i>
                    <h3>Pemahaman Ilmu Agama</h3>
                    <p>
                        Santri memahami ilmu fiqih, tauhid, akhlak, hadits, tafsir,
                        tarikh, dan aswaja sesuai kurikulum Madrasah Diniyah.
                    </p>
                </div>
                <div class="target-card">
                    <i class="fa-solid fa-user-check"></i>
                    <h3>Akhlakul Karimah</h3>
                    <p>
                        Membentuk santri yang berakhlakul karimah, disiplin beribadah,
                        serta mampu mengamalkan ilmu dalam kehidupan sehari-hari.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section class="cta">
        <h2>Mendalami Ilmu Agama, Membentuk Generasi Berakhlakul Karimah</h2>
        <p>
            Bergabunglah bersama Madrasah Diniyah untuk mempelajari ilmu-ilmu keislaman,
            membaca kitab, dan membentuk karakter Islami sebagai bekal kehidupan.
        </p>

        <div class="cta-button">
            <a href="{{ route('informasi-pendaftaran') }}" class="btn">
                Daftar Sekarang
            </a>

            <a href="{{ route('guru') }}" class="btn fadee">
                Hubungi Kami
            </a>
        </div>
    </section>
    @include('components.footer')
@endsection
