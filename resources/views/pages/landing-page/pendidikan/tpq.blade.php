@extends('layout.app')

@include('components.header')

@section('content')
    @include('components.hero', [
        'badge' => 'Jenjang Pendidikan Non Formal',
        'title' => 'Taman Pendidikan' ,
        'titlespan' => 'Al-Qur\'an',
        'subtitle' =>
            'Program dasar baca tulis Al-Qur\'an, hafalan doa harian, dan pembiasaan adab islami untuk anak-anak dalam suasana belajar yang ramah dan menyenangkan.',
    ])

    <section class="profile-tpq">
        <div class="container">
            <div class="profile-wrapper">
                <div class="school-image">
                    <img src="{{ asset('assets/tpq.jpg') }}" alt="Kegiatan Taman Pendidikan Al-Qur'an">
                </div>
                <div class="school-content">
                    <span class="section-kicker">Tentang TPQ</span>
                    <h2>Membangun Kedekatan Anak dengan Al-Qur'an Sejak Dini</h2>
                    <p>
                        TPQ Darussholihin menjadi ruang belajar awal bagi anak untuk mengenal huruf hijaiyah,
                        membaca Al-Qur'an dengan tartil, menghafal doa pilihan, serta membiasakan akhlak baik
                        dalam keseharian.
                    </p>
                    <div class="school-info">
                        <div class="info-item">
                            <i class="fa-solid fa-book-open-reader"></i>
                            <span>Baca Tulis Al-Qur'an</span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-hands-praying"></i>
                            <span>Doa & Ibadah Harian</span>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-heart"></i>
                            <span>Pembinaan Akhlak</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="program-tpq">
        <div class="container">
            <div class="section-title">
                <span>Fokus Pembelajaran</span>
                <h2>Materi TPQ Darussholihin</h2>
                <p>
                    Materi disusun bertahap agar anak merasa nyaman belajar, percaya diri membaca,
                    dan terbiasa mempraktikkan nilai Islam dalam aktivitas sehari-hari.
                </p>
            </div>

            <div class="program-grid-tpq">
                <div class="program-card">
                    <div class="icon"><i class="fa-solid fa-spell-check"></i></div>
                    <h3>Pengenalan Huruf Hijaiyah</h3>
                    <p>Anak belajar mengenal huruf, makharijul huruf, dan latihan membaca dengan metode bertahap.</p>
                </div>
                <div class="program-card">
                    <div class="icon"><i class="fa-solid fa-book-quran"></i></div>
                    <h3>Tahsin Bacaan Dasar</h3>
                    <p>Pembiasaan membaca Al-Qur'an secara tartil dengan pendampingan ustadz dan ustadzah.</p>
                </div>
                <div class="program-card">
                    <div class="icon"><i class="fa-solid fa-mosque"></i></div>
                    <h3>Praktik Ibadah</h3>
                    <p>Latihan wudhu, shalat, doa harian, dan adab agar anak terbiasa beribadah dengan benar.</p>
                </div>
                <div class="program-card">
                    <div class="icon"><i class="fa-solid fa-children"></i></div>
                    <h3>Akhlak & Kemandirian</h3>
                    <p>Pembinaan sikap sopan, disiplin, berani tampil, dan saling menghormati teman.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="education-documentation tpq-documentation">
        <div class="container">
            <div class="documentation-copy">
                <span>Dokumentasi</span>
                <h2>Suasana Belajar TPQ yang Hangat dan Menyenangkan</h2>
                <p>
                    Anak-anak belajar mengenal Al-Qur'an melalui pendampingan yang ramah,
                    kegiatan praktik ibadah, dan pembiasaan adab dalam keseharian.
                </p>
            </div>

            <div class="tpq-card-fan" aria-label="Dokumentasi kegiatan TPQ">
                <figure>
                    <img src="{{ asset('assets/tpq1.jpeg') }}" alt="Pembelajaran TPQ Darussholihin">
                </figure>
                <figure>
                    <img src="{{ asset('assets/tpq2.jpeg') }}" alt="Kegiatan anak TPQ Darussholihin">
                </figure>
                <figure>
                    <img src="{{ asset('assets/tpq3.jpeg') }}" alt="Pendampingan santri TPQ">
                </figure>
            </div>
        </div>
    </section>

    <section class="cta">
        <h2>Mulai Perjalanan Qur'ani Anak Bersama TPQ Darussholihin</h2>
        <p>Daftarkan putra-putri Anda untuk belajar Al-Qur'an dengan bimbingan yang hangat dan terarah.</p>
        <div class="cta-button">
            <a href="{{ route('informasi-pendaftaran') }}" class="btn">Daftar Sekarang</a>
            <a href="{{ route('guru') }}" class="btn fadee">Hubungi Kami</a>
        </div>
    </section>

    @include('components.footer')
@endsection
