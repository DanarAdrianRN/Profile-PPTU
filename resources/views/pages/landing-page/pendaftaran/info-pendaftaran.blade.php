@extends('layout.app')

@include('components.header')

@section('content')
    <section class="pendaftaran">
        <div class="container">

            <!-- HEADER -->
            <div class="pendaftaran-header">
                <div class="content">
                    <h2>{{ $periodeAktif?->nama_periode ?? 'Pendaftaran Santri Baru' }}</h2>
                    <p>Yayasan Tarbiyatul Ulum Sumursongo</p>

                    <div class="promo">
                        <i class="fa-solid fa-gift"></i>
                        Penawaran Spesial {{ $promos?->nama_promo ?? 'Masih belum ada ' }}
                    </div>
                </div>

                <div class="icon-bg">
                    <i class="fa-solid fa-gift"></i>
                </div>
            </div>

        </div>
    </section>

    <section class="gelombang">
        <div class="container">
            <div class="jadwal-section">
                <h3 class="section-title">
                    <i class="fa-regular fa-calendar"></i>
                    Jadwal Pendaftaran
                </h3>
                <div class="jadwal-grid">
                    @forelse ($gelombangs as $item)
                        <div class="jadwal-card
                            @if ($item->status == 'aktif')
                                active
                            @endif
                        ">
                            {{-- BADGE STATUS --}}
                            @if ($item->status == 'aktif')
                                <span class="badge">
                                    AKTIF
                                </span>
                            @elseif ($item->status == 'akan_datang')
                                <span class="badge warning">
                                    AKAN DATANG
                                </span>
                            @else
                                <span class="badge danger">
                                    DITUTUP
                                </span>
                            @endif
                            {{-- NAMA --}}
                            <h4>
                                {{ $item->nama_gelombang }}
                            </h4>
                            {{-- TANGGAL --}}
                            <div class="tanggal">
                                <i class="fa-regular fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }}
                                -
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}
                            </div>
                            @php
                                $promo = $item->promos
                                    ->where('is_active', true)
                                    ->first();
                            @endphp

                            @if($promo)
                                <div class="diskon @if($loop->even) biru @endif">
                                    <i class="fa-solid fa-tag"></i>
                                    {{ $promo->nama_promo }}
                                </div>

                                <p>
                                    @if($promo->kuota)
                                        Berlaku untuk {{ $promo->kuota }} pendaftar pertama
                                    @else
                                        Berlaku untuk seluruh pendaftar
                                    @endif
                                </p>
                            @else
                                <p>
                                    Tidak ada informasi promo
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="jadwal-card">
                            <h4>
                                Belum Ada Gelombang
                            </h4>
                            <p>
                                Informasi gelombang pendaftaran belum tersedia.
                            </p>
                        </div>
                    @endforelse
                </div>
                @if ($jadwalPendaftarans->isNotEmpty())
                    <div class="timeline-pendaftaran">
                        @foreach ($jadwalPendaftarans as $jadwal)
                            <div class="timeline-item">
                                <div class="icon">
                                    <i class="fa-regular fa-calendar"></i>
                                </div>
                                <h5>{{ $jadwal->nama_jadwal }}</h5>
                                <span>{{ $jadwal->tanggal->translatedFormat('d F Y') }}</span>
                            </div>

                            @if (! $loop->last)
                                <div class="timeline-line"></div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="biaya-pendaftaran">
        <div class="container">

            <div class="section-heading">
                <h2>Rincian Biaya Pendaftaran</h2>
                <p>Informasi biaya administrasi santri baru Yayasan Tarbiyatul Ulum</p>
            </div>

            <div class="biaya-grid">
            <!-- SMP -->
            <div class="biaya-card">

                <div class="card-header">

                    <h3>

                        <i class="fa-solid fa-school"></i>

                        SMP Ma'arif Darus Sholihin

                    </h3>

                    <span class="badge">
                        Tahun Ajaran 2026/2027
                    </span>

                </div>

                <div class="table-wrapper">

                    <table>

                        <tbody>

                            {{-- BIAYA TAHUNAN --}}
                            <tr class="category">

                                <td colspan="2">
                                    Biaya Tahunan
                                </td>

                            </tr>

                            @php
                                $totalTahunanSmp = 0;
                            @endphp

                            @foreach ($pembayarans->where('jenjang', 'SMP')->where('kategori', 'Biaya Tahunan') as $item)

                                <tr>

                                    <td>
                                        {{ $item->nama_pembayaran }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>

                                </tr>

                                @php
                                    $totalTahunanSmp += $item->nominal;
                                @endphp

                            @endforeach

                            <tr class="total">

                                <td>
                                    Total Biaya Awal
                                </td>

                                <td>
                                    Rp {{ number_format($totalTahunanSmp, 0, ',', '.') }}
                                </td>

                            </tr>

                            {{-- BIAYA BULANAN --}}
                            <tr class="category">

                                <td colspan="2">
                                    Biaya Bulanan
                                </td>

                            </tr>

                            @php
                                $totalBulananSmp = 0;
                            @endphp

                            @foreach ($pembayarans->where('jenjang', 'SMP')->where('kategori', 'Biaya Bulanan') as $item)

                                <tr>

                                    <td>
                                        {{ $item->nama_pembayaran }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>

                                </tr>

                                @php
                                    $totalBulananSmp += $item->nominal;
                                @endphp

                            @endforeach

                            <tr class="monthly">

                                <td>
                                    Total Bulanan
                                </td>

                                <td>
                                    Rp {{ number_format($totalBulananSmp, 0, ',', '.') }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- SMK -->
            <div class="biaya-card">

                <div class="card-header">

                    <h3>

                        <i class="fa-solid fa-laptop-code"></i>

                        SMK Ma'arif Darus Sholihin

                    </h3>

                    <span class="badge">
                        DKV & TBSM
                    </span>

                </div>

                <div class="table-wrapper">

                    <table>

                        <tbody>

                            {{-- BIAYA TAHUNAN --}}
                            <tr class="category">

                                <td colspan="2">
                                    Biaya Tahunan
                                </td>

                            </tr>

                            @php
                                $totalTahunanSmk = 0;
                            @endphp

                            @foreach ($pembayarans->where('jenjang', 'SMK')->where('kategori', 'Biaya Tahunan') as $item)

                                <tr>

                                    <td>
                                        {{ $item->nama_pembayaran }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>

                                </tr>

                                @php
                                    $totalTahunanSmk += $item->nominal;
                                @endphp

                            @endforeach

                            <tr class="total">

                                <td>
                                    Total Biaya Awal
                                </td>

                                <td>
                                    Rp {{ number_format($totalTahunanSmk, 0, ',', '.') }}
                                </td>

                            </tr>

                            {{-- BIAYA BULANAN --}}
                            <tr class="category">

                                <td colspan="2">
                                    Biaya Bulanan
                                </td>

                            </tr>

                            @php
                                $totalBulananSmk = 0;
                            @endphp

                            @foreach ($pembayarans->where('jenjang', 'SMK')->where('kategori', 'Biaya Bulanan') as $item)

                                <tr>

                                    <td>
                                        {{ $item->nama_pembayaran }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>

                                </tr>

                                @php
                                    $totalBulananSmk += $item->nominal;
                                @endphp

                            @endforeach

                            <tr class="monthly">

                                <td>
                                    Total Bulanan
                                </td>

                                <td>
                                    Rp {{ number_format($totalBulananSmk, 0, ',', '.') }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>
            </div>
        </div>
    </section>

    <section class="syarat-ketentuan">

        <div class="container">

            <!-- TITLE -->
            <div class="section-heading">
                <h2>
                    <i class="fa-solid fa-shield-halved"></i>
                    Syarat dan Ketentuan
                </h2>

                <p>
                    Pastikan seluruh persyaratan dipenuhi sebelum melakukan pendaftaran santri baru.
                </p>
            </div>

            <!-- BOX SYARAT -->
            <div class="syarat-box">

                <ul class="syarat-list">

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Calon santri mendaftar bersama orang tua
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Mengisi formulir pendaftaran dengan lengkap
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Menyerahkan fotocopy Akta Kelahiran 5 lembar
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Menyerahkan fotocopy KTP Orang Tua 5 lembar
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Menyerahkan fotocopy Kartu Keluarga 5 lembar
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Menyerahkan ijazah yang telah dilegalisir 3 lembar
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Menyerahkan NISN 3 lembar
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Menyerahkan KKS / SKTM / PKH / KIP jika memiliki
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Menyerahkan SKL jika ijazah belum tersedia
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Foto berwarna ukuran 3x4 sebanyak 3 lembar
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Foto hitam putih ukuran 3x4 sebanyak 3 lembar
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Stopmap merah untuk SMP dan hijau untuk SMK
                    </li>

                    <li>
                        <i class="fa-regular fa-circle-check"></i>
                        Membayar uang pendaftaran Rp 100.000
                    </li>

                </ul>

            </div>

            <!-- CHECKLIST -->
            <div class="agreement-box" id="agreementBox">

                <label class="checkbox-wrapper" id="checkboxWrapper">

                    <input type="checkbox" id="agreeCheck">

                    <span class="checkmark"></span>

                    <p>
                        Saya telah membaca dan menyetujui
                        <strong>Syarat dan Ketentuan</strong>
                        pendaftaran santri baru.
                    </p>

                </label>

            </div>

        </div>

    </section>

    <section class="alur-pendaftaran">

        <div class="container">
            <div class="alur-box">

                <div class="section-heading">
                    <h2>
                        <i class="fa-solid fa-route"></i>
                        Alur Pendaftaran
                    </h2>

                    <p>
                        Ikuti tahapan pendaftaran santri baru dengan mudah dan terstruktur
                    </p>
                </div>

                <div class="alur-wrapper">

                    <!-- STEP 1 -->
                    <div class="alur-item">
                        <div class="icon">
                            <i class="fa-solid fa-info-circle"></i>
                        </div>

                        <span>Informasi Pendaftaran</span>
                    </div>

                    <div class="alur-item">
                        <div class="icon">
                            <i class="fa-solid fa-file-pen"></i>
                        </div>

                        <span>Isi Formulir</span>
                    </div>

                    <!-- STEP 2 -->
                    <div class="alur-item">
                        <div class="icon">
                            <i class="fa-solid fa-file-arrow-up"></i>
                        </div>

                        <span>Upload Dokumen</span>
                    </div>

                    <!-- STEP 3 -->
                    <div class="alur-item">
                        <div class="icon">
                            <i class="fa-solid fa-wallet"></i>
                        </div>

                        <span>Pembayaran</span>
                    </div>

                    <!-- STEP 4 -->
                    <div class="alur-item">
                        <div class="icon">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>

                        <span>Tes Seleksi</span>
                    </div>

                    <!-- STEP 5 -->
                    <div class="alur-item">
                        <div class="icon">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>

                        <span>Pengumuman</span>
                    </div>

                    <!-- STEP 6 -->
                    <div class="alur-item">
                        <div class="icon">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                        <span>Daftar Ulang</span>
                    </div>

                </div>

            </div>
        </div>

    </section>

    <!-- MENU PENDAFTARAN -->
    <section class="menu-section">

        <div class="container">

            <div class="menu-pendaftaran">

                <a href="{{ route('form-pendaftaran') }}" class="menu-card primary" id="btnDaftar">

                    <div class="icon">
                        <i class="fa-solid fa-file-pen"></i>
                    </div>

                    <div class="text">
                        <h3>Daftar Sekarang</h3>
                        <p>Pendaftaran baru tahun ajaran 2026-2027</p>

                        <span>
                            Mulai Pendaftaran
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>

                </a>

                <a href="{{ route('cek-pendaftaran') }}" class="menu-card secondary">

                    <div class="icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <div class="text">
                        <h3>Cek Pendaftaran</h3>
                        <p>Sudah daftar? Cek status pendaftaran Anda</p>

                        <span>
                            Cek Status Sekarang
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>

                </a>

            </div>

        </div>

    </section>

    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const daftarBtn = document.getElementById("btnDaftar");
                const agreeCheck = document.getElementById("agreeCheck");
                const agreementBox = document.getElementById("agreementBox");

                daftarBtn.addEventListener("click", function(e) {

                    if (!agreeCheck.checked) {

                        e.preventDefault();

                        // scroll ke checklist
                        agreementBox.scrollIntoView({
                            behavior: "smooth",
                            block: "center"
                        });

                        // kasih warna merah sementara
                        agreementBox.classList.add("warning");

                        setTimeout(() => {
                            agreementBox.classList.remove("warning");
                        }, 2500);

                    }

                });

            });
        </script>
    @endpush
    @include('components.footer')
@endsection
