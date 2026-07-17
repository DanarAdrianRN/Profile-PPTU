@extends('layout.app')

@include('components.header')

@section('content')
    @include('components.hero', [
        'badge' => 'Jenjang Pendidikan Formal',
        'title' => 'SMP Ma\'arif',
        'titlespan' => 'Darussholihin',
        'subtitle' =>
            'Pendidikan menengah pertama berbasis pesantren yang memadukan kurikulum nasional, pendidikan keislaman, dan pembinaan karakter untuk membentuk generasi yang berilmu dan berakhlakul karimah.',
    ])

    <section class="profile-smp">
        <div class="container">
            <div class="profile-wrapper">
                <div class="school-image">
                    <img src="{{ asset('assets/smp.png') }}" alt="SMP Ma'arif Darussholihin">
                </div>
                <div class="school-content">
                    <span class="section-kicker">
                        Tentang SMP
                    </span>
                    <h2>
                        Pendidikan Formal Berbasis Pesantren Sejak Tahun 2008
                    </h2>
                    <p>
                        SMP Ma'arif Darussholihin didirikan pada tahun 2008 sebagai
                        lembaga pendidikan menengah pertama yang memadukan kurikulum
                        nasional dengan pendidikan keislaman. Selain membekali peserta
                        didik dengan ilmu pengetahuan umum, sekolah juga menanamkan
                        nilai-nilai akhlakul karimah, kedisiplinan, serta pembiasaan
                        ibadah dalam kehidupan sehari-hari.
                    </p>
                    <div class="school-info">
                        <div class="info-item">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>Berdiri Tahun 2008</span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-mosque"></i>
                            <span>Pembinaan Karakter Islami</span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-book-open"></i>
                            <span>Kurikulum Nasional & Keislaman</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="visi-misi">
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
    </section>

    <section class="keunggulan-smp">
        <div class="container">

            <div class="section-title">
                <span>Keunggulan Sekolah</span>
                <h2>Mengapa Memilih SMP Ma'arif Darussholihin?</h2>
                <p>
                    Pendidikan formal yang terintegrasi dengan kehidupan pesantren
                    untuk membentuk generasi yang berilmu, berakhlak, dan mandiri.
                </p>
            </div>

            <div class="target-grid">

                <div class="target-card">
                    <i class="fa-solid fa-school"></i>
                    <h3>Pendidikan Berbasis Pesantren</h3>
                    <p>
                        Seluruh peserta didik mengikuti pendidikan formal sekaligus
                        menjalani kehidupan di lingkungan pondok pesantren secara terpadu.
                    </p>
                </div>

                <div class="target-card">
                    <i class="fa-solid fa-book-open"></i>
                    <h3>Kurikulum Nasional & Keislaman</h3>
                    <p>
                        Mengintegrasikan kurikulum nasional dengan pendidikan
                        keislaman untuk membentuk peserta didik yang berilmu
                        dan berakhlakul karimah.
                    </p>
                </div>

                <div class="target-card">
                    <i class="fa-solid fa-mosque"></i>
                    <h3>Pembiasaan Ibadah</h3>
                    <p>
                        Santri dibiasakan melaksanakan ibadah berjamaah,
                        membaca Al-Qur'an, serta menerapkan adab Islami
                        dalam kehidupan sehari-hari.
                    </p>
                </div>

                <div class="target-card">
                    <i class="fa-solid fa-seedling"></i>
                    <h3>Pembinaan Karakter</h3>
                    <p>
                        Kehidupan di pesantren membentuk pribadi yang disiplin,
                        mandiri, bertanggung jawab, dan mampu hidup bermasyarakat.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <section class="education-documentation smp-documentation">
        <div class="container">
            <div class="documentation-layout">
                <div class="documentation-copy">
                    <span>Dokumentasi</span>
                    <h2>Keseharian Belajar dalam Lingkungan Pesantren</h2>
                    <p>
                        Dokumentasi kegiatan SMP menggambarkan pembelajaran formal,
                        pembinaan karakter, dan pengalaman santri yang tumbuh dalam
                        suasana pesantren.
                    </p>
                </div>

                <div class="smp-mosaic" aria-label="Dokumentasi SMP Ma'arif Darussholihin">
                    <img src="{{ asset('assets/smp1.JPG') }}" alt="SMP Ma'arif Darussholihin">
                    <img src="{{ asset('assets/smp2.jpeg') }}" alt="Kegiatan kedisiplinan siswa SMP">
                    <img src="{{ asset('assets/smp3.jpeg') }}" alt="Aktivitas santri SMP di pesantren">
                    <img src="{{ asset('assets/smp4.JPG') }}" alt="Pembelajaran santri SMP">
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <h2>Mulai Langkah Menuju Masa Depan yang Berprestasi</h2>
        <p>
            Bergabunglah bersama SMP Ma'arif Darussholihin dan wujudkan pendidikan yang unggul serta berkarakter Islami.
        </p>

        <div class="cta-button">
            <a href="{{ route('informasi-pendaftaran') }}" class="btn">Daftar Sekarang</a>
            <a href="{{ route('guru') }}" class="btn fadee">Hubungi Kami</a>
        </div>
    </section>
    @include('components.footer')
@endsection
