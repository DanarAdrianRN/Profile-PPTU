@extends('layout.app')

@include('components.header')

@section('content')
    <section class="kegiatan-page">
        <div class="kegiatan-hero">
            <div class="container">
                <div class="hero-grid">
                    <div class="hero-copy">
                        <span class="hero-badge">
                            <i class="fa-solid fa-calendar-days"></i>
                            Kegiatan Santri
                        </span>
                        <h1>Kegiatan & Ekstrakurikuler PPTU Sumursongo</h1>
                        <p>
                            Rutinitas santri disusun untuk menyeimbangkan ibadah, pendidikan formal,
                            madrasah diniyah, pembiasaan akhlak, dan pengembangan minat bakat.
                        </p>
                    </div>

                    <div class="hero-card">
                        <div class="hero-image">
                            <img src="{{ asset('assets/kegiatan.JPG') }}" alt="Kegiatan santri PPTU Sumursongo">
                        </div>
                        <div class="hero-stats">
                            <div>
                                <strong>03.30</strong>
                                <span>Mulai Harian</span>
                            </div>
                            <div>
                                <strong>4</strong>
                                <span>Pola Kegiatan</span>
                            </div>
                            <div>
                                <strong>8+</strong>
                                <span>Ekstrakurikuler</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="activity-overview">
            <div class="container">
                <div class="section-heading">
                    <span>Agenda Pesantren</span>
                    <h2>Ritme Kegiatan Santri</h2>
                    <p>
                        Kegiatan di pesantren berjalan berlapis: harian untuk kedisiplinan,
                        mingguan untuk pembiasaan, bulanan untuk tradisi keilmuan, dan tahunan
                        untuk momen besar pesantren.
                    </p>
                </div>

                <div class="overview-grid">
                    <div class="overview-card">
                        <i class="fa-solid fa-mosque"></i>
                        <h3>Ibadah Berjamaah</h3>
                        <p>Shalat berjamaah, wirid, dan pembiasaan dzikir menjadi fondasi harian santri.</p>
                    </div>
                    <div class="overview-card">
                        <i class="fa-solid fa-book-quran"></i>
                        <h3>Madrasah Diniyah</h3>
                        <p>Pembelajaran Al-Qur'an, kitab, dan pendalaman agama dilakukan secara terjadwal.</p>
                    </div>
                    <div class="overview-card">
                        <i class="fa-solid fa-users-gear"></i>
                        <h3>Pengembangan Diri</h3>
                        <p>Santri diberi ruang mengasah kepemimpinan, seni, olahraga, dan keterampilan.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="daily-schedule">
            <div class="container">
                <div class="schedule-layout">
                    <div class="schedule-copy">
                        <span>Kegiatan Harian</span>
                        <h2>Sehari Penuh Dalam Pembinaan</h2>
                        <p>
                            Jadwal harian membantu santri membangun disiplin sejak sebelum subuh
                            hingga waktu istirahat malam.
                        </p>
                    </div>

                    <div class="timeline-card">
                        @php
                            $dailyActivities = [
                                ['time' => '03.30 - 04.00', 'title' => "Persiapan Shalat Subuh Berjama'ah"],
                                ['time' => '04.00 - 05.00', 'title' => 'Shalat Subuh Berjamaah'],
                                ['time' => '05.00 - 06.00', 'title' => 'Awradan Bersama dan Ngaji Wekton'],
                                ['time' => '06.00 - 06.30', 'title' => 'Piket Pagi'],
                                ['time' => '06.30 - 06.45', 'title' => 'Makan Pagi'],
                                ['time' => '06.45 - 07.00', 'title' => 'Shalat Dhuha Berjamaah'],
                                ['time' => '07.00 - 13.30', 'title' => 'Sekolah Formal'],
                                ['time' => '13.30 - 14.30', 'title' => 'Istirahat'],
                                ['time' => '14.30 - 14.45', 'title' => 'Persiapan Madrasah Diniyah'],
                                ['time' => '14.45 - 15.00', 'title' => 'Doa Bersama Sebelum Pelajaran'],
                                ['time' => '15.00 - 15.15', 'title' => 'Lalaran Nadham'],
                                ['time' => '15.15 - 17.00', 'title' => 'Madrasah Diniyah'],
                                ['time' => '17.00 - 18.00', 'title' => 'Makan Sore'],
                                ['time' => '18.00 - 18.30', 'title' => "Shalat Maghrib Berjama'ah"],
                                ['time' => '18.30 - 19.30', 'title' => "Madrasah Al-Qur'an"],
                                ['time' => '19.30 - 20.00', 'title' => "Shalat Isya' Berjama'ah"],
                                ['time' => '20.00 - 21.00', 'title' => 'Taqrar Sekolah Formal'],
                                ['time' => '21.00 - 22.00', 'title' => 'Taqrar Madin'],
                                ['time' => '22.00 - 03.30', 'title' => 'Istirahat'],
                            ];
                        @endphp

                        @foreach ($dailyActivities as $activity)
                            <div class="timeline-item">
                                <div class="time">{{ $activity['time'] }}</div>
                                <div class="dot"></div>
                                <div class="activity">{{ $activity['title'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="periodic-activities">
            <div class="container">
                <div class="section-heading">
                    <span>Kegiatan Berkala</span>
                    <h2>Mingguan, Bulanan, dan Tahunan</h2>
                    <p>
                        Agenda berkala menjaga tradisi pesantren tetap hidup sekaligus memberi ruang
                        santri berlatih tanggung jawab sosial dan spiritual.
                    </p>
                </div>

                <div class="periodic-grid">
                    <div class="periodic-card">
                        <div class="card-head">
                            <i class="fa-solid fa-calendar-week"></i>
                            <h3>Kegiatan Mingguan</h3>
                        </div>
                        <ul>
                            <li>Malam Ahad bada Maghrib: pelatihan memimpin tahlil di masing-masing kelas Madrasah Al-Qur'an.</li>
                            <li>Malam Ahad bada Isya: acara Berfatwa atau Tabligh.</li>
                            <li>Malam Selasa bada Isya: tilawatil Qur'an.</li>
                            <li>Malam Jumat bada Maghrib: ziarah ke makam KH. Sayfuddin Muhammad.</li>
                            <li>Malam Jumat bada Isya: pembacaan maulid sesuai jadwal.</li>
                            <li>Setiap satu minggu sekali: kegiatan syawir per kelas diniyah.</li>
                        </ul>
                    </div>

                    <div class="periodic-card highlight">
                        <div class="card-head">
                            <i class="fa-solid fa-moon"></i>
                            <h3>Kegiatan Bulanan</h3>
                        </div>
                        <ul>
                            <li>Malam 11 bulan Hijriyah bada Maghrib: pembacaan Dzikrul Ghofilin.</li>
                            <li>Malam 11 bulan Hijriyah bada Isya: pembacaan Manaqib Nurul Burhan.</li>
                            <li>Setiap Ahad Pon bada Subuh: ziarah dan tahlil ke makam para masyayikh pendiri pondok pesantren.</li>
                            <li>Setiap malam Jumat Pon: ziarah ke makam KH. Sayfuddin Muhammad.</li>
                        </ul>
                    </div>

                    <div class="periodic-card">
                        <div class="card-head">
                            <i class="fa-solid fa-star-and-crescent"></i>
                            <h3>Kegiatan Tahunan</h3>
                        </div>
                        <ul>
                            <li>Pengajian kitab setiap bulan Ramadhan.</li>
                            <li>Tasmi' Bil Ghaib Juz 30.</li>
                            <li>ZARKASI atau ziarah dan rekreasi santri.</li>
                            <li>Celebration dan resepsi akhirussanah.</li>
                            <li>Pengajian haul thariqoh.</li>
                            <li>Pertemuan alumni.</li>
                            <li>PHBN dan PHBI.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="extracurricular">
            <div class="container">
                <div class="section-heading">
                    <span>Ekstrakurikuler</span>
                    <h2>Ruang Minat dan Bakat Santri</h2>
                    <p>
                        Kegiatan ekstrakurikuler membantu santri berani tampil, bekerja sama,
                        dan mengembangkan potensi di luar pembelajaran kelas.
                    </p>
                </div>

                <div class="extra-showcase">
                    <div class="extra-feature">
                        <div class="feature-image">
                            <img src="{{ asset('assets/drumband.JPG') }}" alt="Kegiatan ekstrakurikuler santri">
                        </div>
                        <div class="feature-content">
                            <span>Aktif, kreatif, berkarakter</span>
                            <h3>Ekstrakurikuler menjadi ruang santri melatih keberanian, disiplin, dan kerja sama.</h3>
                            <div class="feature-points">
                                <div>
                                    <strong>Seni</strong>
                                    <small>Hadroh, drumband, kaligrafi</small>
                                </div>
                                <div>
                                    <strong>Prestasi</strong>
                                    <small>Tilawah, futsal, pramuka</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="extra-grid">
                        @php
                            $extras = [
                                [
                                    'icon' => 'fa-drum',
                                    'title' => 'Seni Hadroh',
                                    'label' => 'Seni Islami',
                                    'desc' => 'Melatih kekompakan vokal, irama, dan adab tampil.',
                                ],
                                [
                                    'icon' => 'fa-music',
                                    'title' => 'Drumband',
                                    'label' => 'Kedisiplinan',
                                    'desc' => 'Membangun ritme, barisan, fokus, dan percaya diri.',
                                ],
                                [
                                    'icon' => 'fa-pen-nib',
                                    'title' => 'Seni Kaligrafi',
                                    'label' => 'Kreativitas',
                                    'desc' => 'Mengasah ketelitian, rasa seni, dan kecintaan pada aksara Arab.',
                                ],
                                [
                                    'icon' => 'fa-book-open-reader',
                                    'title' => "Tilawatil Qur'an",
                                    'label' => 'Qurani',
                                    'desc' => 'Membina bacaan, lagu, tajwid, dan keberanian membaca di hadapan umum.',
                                ],
                                [
                                    'icon' => 'fa-campground',
                                    'title' => 'Pramuka',
                                    'label' => 'Karakter',
                                    'desc' => 'Melatih kemandirian, kepemimpinan, dan tanggung jawab sosial.',
                                ],
                                [
                                    'icon' => 'fa-futbol',
                                    'title' => 'Futsal',
                                    'label' => 'Olahraga',
                                    'desc' => 'Menjaga kebugaran sekaligus melatih strategi dan sportivitas.',
                                ],
                                [
                                    'icon' => 'fa-people-group',
                                    'title' => 'Leadership PK IPNU IPPNU',
                                    'label' => 'Organisasi',
                                    'desc' => 'Ruang kaderisasi untuk santri belajar memimpin dan bermusyawarah.',
                                ],
                                [
                                    'icon' => 'fa-scroll',
                                    'title' => 'Risalatul Mahidl dan Keputrian',
                                    'label' => 'Pembinaan',
                                    'desc' => 'Pendampingan fikih, adab, dan wawasan keputrian secara terarah.',
                                ],
                            ];
                        @endphp

                        @foreach ($extras as $extra)
                            <div class="extra-card">
                                <div class="extra-icon">
                                    <i class="fa-solid {{ $extra['icon'] }}"></i>
                                </div>
                                <div class="extra-content">
                                    <span>{{ $extra['label'] }}</span>
                                    <h3>{{ $extra['title'] }}</h3>
                                    <p>{{ $extra['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="activity-gallery">
            <div class="container">
                <div class="gallery-layout">
                    <div class="gallery-copy">
                        <span>Dokumentasi</span>
                        <h2>Belajar, Berkhidmah, dan Bertumbuh Bersama</h2>
                        <p>
                            Setiap kegiatan diarahkan untuk membentuk santri yang disiplin,
                            berakhlak, mandiri, dan siap berperan di tengah masyarakat.
                        </p>
                    </div>
                    <div class="gallery-grid">
                        <img src="{{ asset('assets/kegiatan1.jpeg') }}" alt="Dokumentasi kegiatan santri">
                        <img src="{{ asset('assets/kegiatan2.jpeg') }}" alt="Dokumentasi kegiatan pesantren">
                        <img src="{{ asset('assets/kegiatan3.jpeg') }}" alt="Dokumentasi ekstrakurikuler">
                    </div>
                </div>
            </div>
        </section>
    </section>

    @include('components.footer')
@endsection
