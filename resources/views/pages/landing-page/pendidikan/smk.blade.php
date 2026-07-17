@extends('layout.app')

@include('components.header')

@section('content')
    @include('components.hero', [
        'badge' => 'Jenjang Pendidikan Formal',
        'title' => 'SMK Ma\'arif',
        'titlespan' => 'Darussholihin',
        'subtitle' =>
            'Pendidikan kejuruan berbasis pesantren yang memadukan kurikulum nasional, kompetensi keahlian, dan pembinaan karakter Islami untuk mempersiapkan lulusan yang siap berkarya.',
    ])

    <section class="profile-smk">
        <div class="container">
            <div class="profile-wrapper">
                <div class="school-image">
                    <img src="{{ asset('assets/smk.png') }}" alt="SMK Ma'arif Darussholihin">
                </div>
                <div class="school-content">
                    <span class="section-kicker">
                        Tentang SMK
                    </span>
                    <h2>
                        Pendidikan Kejuruan Berbasis Pesantren
                    </h2>
                    <p>
                        SMK Ma'arif Darussholihin didirikan pada tahun 2011 sebagai
                        lembaga pendidikan kejuruan yang memadukan kurikulum nasional,
                        kompetensi keahlian, dan pendidikan keislaman dalam lingkungan
                        pondok pesantren. Seluruh peserta didik merupakan santri yang
                        menetap di pondok sehingga pembelajaran di kelas berjalan
                        selaras dengan pembinaan karakter, kedisiplinan, dan nilai-nilai
                        Islami.
                    </p>
                    <div class="school-info">
                        <div class="info-item">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>Berdiri Tahun 2011</span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-laptop-code"></i>
                            <span>2 Program Keahlian</span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-school"></i>
                            <span>Pendidikan Berbasis Pesantren</span>
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
                        Mencetak anak didik yang beriman, berilmu, berketerampilan, berwawasan luas yang berhaluan ahlus sunah wal jama'ah An-Nahdliyyah, dan berakhlak mulia.
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
                        <li>Melaksanakan kegiatan pembelajaran selaras dengan melaksanakan kewajiban beribadah baik di sekolah, kegiatan ekstrakurikuler, dan prakerin</li>
                        <li>Menyusun kurikulum, program pembelajaran, dan laporan penilaian hasil pembelajaran dengan akurat dan tepat waktu</li>
                        <li>Memberikan keteladanan sikap disiplin belajar dan perilaku positif kepada peserta didik dalam keseluruhan proses kegiatanbelajar mengajar </li>
                        <li>Melakukan sharing terhadap DU/DI, lembaga pendidikan terkait, dan masyarakat dalam menyusun dan melaksanakan program kegiatan belajar mengajar</li>
                        <li>Melengkapi sarana prasarana praktek</li>
                        <li>Mengadakan kegiatan pembelajaran yang seimbang antara teori dan praktek</li>
                        <li>Memiliki DU/DI yang berkualitas dalam kegiatan prakerin</li>
                        <li>Menjalankan prinsip tawazun, tawasuth, tasamuh, i'tidal, dan amal ma'rif nahi munkar dalam kegiatan belajar mengajar</li>
                        <li>Mencontoh akhlaq nabi muhammad SAW, dalam kegiatan belajar mengajar dan dalam kegiatan sehari-hari</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="jurusan-smk">
        <div class="container">

            <div class="section-title">
                <span>Program Keahlian</span>
                <h2>Jurusan SMK</h2>
                <p>
                    SMK Ma'arif Darussholihin memiliki dua program keahlian yang
                    dirancang untuk membekali santri dengan keterampilan praktis serta kesiapan menghadapi dunia kerja maupun
                    melanjutkan pendidikan ke jenjang berikutnya.
                </p>
            </div>

            <div class="jurusan-grid">
                <!-- ==================== DKV ==================== -->
                <div class="jurusan-card dkv">
                    <div class="top-image">
                        <img src="{{ asset('assets/smk-dkv.jpeg') }}" alt="Jurusan DKV">
                    </div>
                    <div class="jurusan-header">
                        <div class="icon">
                            <img src="{{ asset('assets/dkv.png') }}" alt="Jurusan DKV">
                        </div>
                        <div>
                            <h3>DKV</h3>
                            <span>Desain Komunikasi Visual</span>
                        </div>
                    </div>
                    <p class="desc">
                        Program keahlian yang mempelajari komunikasi visual melalui
                        media digital maupun cetak. Santri dibekali kemampuan
                        desain, pengolahan media visual, serta produksi karya kreatif
                        sesuai perkembangan industri.
                    </p>
                    <div class="jurusan-section">
                        <h4>
                            <i class="fa-solid fa-book-open"></i>
                            Kompetensi Yang Dipelajari
                        </h4>
                        <div class="tags">
                            <span>Prinsip Dasar Desain dan Komunikasi</span>
                            <span>Perangkat Lunak Desain</span>
                            <span>Desain Brief</span>
                            <span>Karya Desain</span>
                            <span>Proses Produksi Desain</span>
                        </div>
                    </div>
                    <div class="jurusan-section">
                        <h4>
                            <i class="fa-solid fa-building"></i>
                            Fasilitas Praktik
                        </h4>
                        <div class="tags">
                            <span>Lab DKV</span>
                            <span>Komputer</span>
                            <span>Kamera Canon DSLR</span>
                            <span>Kamera Video Sony</span>
                            <span>Lighting Studio</span>
                        </div>
                    </div>
                </div>
                <!-- ==================== TBSM ==================== -->
                <div class="jurusan-card tbsm">
                    <div class="top-image">
                        <img src="{{ asset('assets/smk-tsm.PNG') }}" alt="Jurusan TBSM">
                    </div>
                    <div class="jurusan-header">
                        <div class="icon">
                            <img src="{{ asset('assets/tsm.png') }}" alt="Jurusan TBSM">
                        </div>
                        <div>
                            <h3>TSM</h3>
                            <span>Teknik Sepeda Motor</span>
                        </div>
                    </div>
                    <p class="desc">
                        Program keahlian yang membekali santri dengan kemampuan
                        perawatan, perbaikan, dan pengelolaan sepeda motor melalui
                        pembelajaran teori serta praktik bengkel secara langsung.
                    </p>
                    <div class="jurusan-section">
                        <h4>
                            <i class="fa-solid fa-book-open"></i>
                            Kompetensi Yang Dipelajari
                        </h4>
                        <div class="tags">
                            <span>Perawatan Mesin</span>
                            <span>Kelistrikan Kendaraan</span>
                            <span>Perawatan Sasis</span>
                            <span>Sistem Pemindah Tenaga</span>
                            <span>Pengelolaan Bengkel</span>
                        </div>
                    </div>
                    <div class="jurusan-section">
                        <h4>
                            <i class="fa-solid fa-building"></i>
                            Fasilitas Praktik
                        </h4>
                        <div class="tags">
                            <span>Bengkel TSM</span>
                            <span>Motor Matic</span>
                            <span>Motor Bebek</span>
                            <span>Peralatan Servis</span>
                            <span>Mesin Las</span>
                            <span>Peralatan Bengkel Lengkap</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="keunggulan-smk">
        <div class="container">
            <div class="section-title">
                <span>Keunggulan Sekolah</span>
                <h2>Mengapa Memilih SMK Ma'arif Darussholihin?</h2>
                <p>
                    Pendidikan kejuruan yang memadukan kompetensi keahlian,
                    pembinaan karakter Islami, dan kehidupan pesantren untuk
                    mempersiapkan lulusan yang siap melanjutkan pendidikan maupun memasuki dunia kerja.
                </p>
            </div>
            <div class="target-grid">
                <div class="target-card">
                    <i class="fa-solid fa-laptop-code"></i>
                    <h3>Kompetensi Keahlian</h3>
                    <p>
                        Program DKV dan TSM dirancang untuk membekali santri
                        dengan keterampilan sesuai kebutuhan dunia industri.
                    </p>
                </div>
                <div class="target-card">
                    <i class="fa-solid fa-school"></i>
                    <h3>Pendidikan Berbasis Pesantren</h3>
                    <p>
                        Seluruh peserta didik merupakan santri yang tinggal
                        di pondok sehingga pembelajaran akademik berjalan
                        selaras dengan pembinaan karakter Islami.
                    </p>
                </div>
                <div class="target-card">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <h3>Praktik dan Pengalaman</h3>
                    <p>
                        Pembelajaran didukung kegiatan praktik sesuai bidang
                        keahlian agar santri memiliki pengalaman dan keterampilan
                        yang aplikatif.
                    </p>
                </div>
                <div class="target-card">
                    <i class="fa-solid fa-user-graduate"></i>
                    <h3>Siap Berkarya</h3>
                    <p>
                        Membekali santri untuk melanjutkan pendidikan ke jenjang
                        yang lebih tinggi maupun memasuki dunia kerja sesuai
                        kompetensi yang dimiliki.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="education-documentation smk-documentation">
        <div class="container">
            <div class="documentation-layout">
                <div class="documentation-copy">
                    <span>Dokumentasi</span>
                    <h2>Ruang Praktik, Kreativitas, dan Kesiapan Berkarya</h2>
                    <p>
                        Kegiatan SMK mempertemukan pembelajaran pesantren dengan praktik
                        kejuruan, sehingga santri terbiasa belajar, mencoba, dan berkarya.
                    </p>
                </div>

                <div class="smk-studio-board" aria-label="Dokumentasi SMK Ma'arif Darussholihin">
                    <figure class="main-shot">
                        <img src="{{ asset('assets/smk1.png') }}" alt="Kegiatan keterampilan santri SMK">
                    </figure>
                    <figure>
                        <img src="{{ asset('assets/smk2.JPG') }}" alt="Pembinaan santri SMK">
                    </figure>
                    <figure>
                        <img src="{{ asset('assets/smk3.JPG') }}" alt="Kegiatan pengembangan diri SMK">
                    </figure>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <h2>Wujudkan Masa Depan Bersama SMK Ma'arif Darussholihin</h2>
        <p>
            Kembangkan kompetensi keahlian sekaligus karakter Islami dalam lingkungan pesantren.
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
