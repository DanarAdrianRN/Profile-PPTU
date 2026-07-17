@extends('layout.app')

@include('components.header')

@section('content')
    <section class="hasil-pendaftaran">

        <div class="container">

            <!-- HEADER -->
            <div class="page-header">

                <h2>
                    Cek Status Pendaftaran
                </h2>

                <p>
                    Lihat status pendaftaran dan hasil tes Anda
                </p>

            </div>

            <!-- STATUS -->
            @php
                $status = $pendaftaran->status ?? 'Menunggu Verifikasi';
                $statusDiterima = str_contains(strtolower($status), 'diterima')
                    || str_contains(strtolower($status), 'lulus');
                $tanggalStatus = optional($pendaftaran->updated_at)->translatedFormat('d-F-Y');
            @endphp

            <div class="status-card {{ $statusDiterima ? 'diterima' : 'menunggu' }}">

                <div class="status-left">

                    <div class="status-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>

                    <div>

                        <h3>{{ $statusDiterima ? 'Selamat! Anda Diterima' : $status }}</h3>

                        <p>{{ $statusDiterima ? 'Silakan daftar ulang' : 'Pantau status pendaftaran Anda secara berkala' }}</p>

                    </div>

                </div>

                <div class="status-right">

                    <span>{{ $pendaftaran->kode_pendaftaran }}</span>

                    <small>
                        {{ $statusDiterima ? 'Diterima' : 'Diperbarui' }} pada {{ $tanggalStatus }}
                    </small>

                </div>

            </div>

            <!-- DATA DIRI -->
            <div class="result-card">

                <div class="card-title">

                    <i class="fa-solid fa-user"></i>

                    <h4>
                        Data Diri Santri
                    </h4>

                </div>

                <div class="biodata-grid">

                    <div class="bio-item">
                        <span>Nama</span>
                        <strong>{{ $pendaftaran->nama_lengkap }}</strong>
                    </div>

                    <div class="bio-item">
                        <span>NISN</span>
                        <strong>{{ $pendaftaran->pendidikan->nisn ?? '-' }}</strong>
                    </div>

                    <div class="bio-item">
                        <span>Jenis Kelamin</span>
                        <strong>{{ $pendaftaran->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</strong>
                    </div>

                    <div class="bio-item">
                        <span>Tanggal Lahir</span>
                        <strong>
                            {{ $pendaftaran->tanggal_lahir ? \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('j F Y') : '-' }}
                        </strong>
                    </div>

                    <div class="bio-item">
                        <span>Program Pilihan</span>
                        <strong>{{ $pendaftaran->pendidikan->jenjang_pendidikan ?? '-' }}</strong>
                    </div>

                    <div class="bio-item">
                        <span>Nama Orang Tua</span>
                        <strong>{{ $orangTua->nama ?? '-' }}</strong>
                    </div>

                    <div class="bio-item">
                        <span>No HP</span>
                        <strong>{{ $pendaftaran->no_hp_ortu ?? '-' }}</strong>
                    </div>

                    <div class="bio-item">
                        <span>Gelombang</span>
                        <strong>
                            {{ $pendaftaran->gelombang ?? 'Gelombang 1' }}
                        </strong>
                    </div>


                    <div class="bio-item full">
                        <span>Alamat</span>
                        <strong>
                            {{ $pendaftaran->alamat ?? '-' }}
                        </strong>
                    </div>

                </div>

            </div>

            <!-- NILAI -->
            <div class="result-card">

                <div class="card-title">

                    <i class="fa-solid fa-chart-column"></i>

                    <h4>
                        Hasil Tes Masuk
                    </h4>

                </div>

                <div class="nilai-grid">

                    @forelse (($hasilTes?->nilaiItems() ?? []) as $namaTes => $nilai)
                        <div class="nilai-item">
                            <span>{{ $namaTes }}</span>
                            <strong>{{ $nilai ?? '-' }}</strong>
                        </div>
                    @empty
                        <div class="nilai-item">
                            <span>Hasil Tes</span>
                            <strong>Belum tersedia</strong>
                        </div>
                    @endforelse

                </div>

                <!-- RATA -->
                <div class="nilai-total">

                    <span>Rata-rata Nilai</span>

                    <h2>{{ $hasilTes?->rata_rata ?? '-' }}</h2>

                    <p>
                        {{ $hasilTes?->predikat ?? 'Menunggu input admin' }}
                    </p>

                </div>

            </div>

            <!-- LANGKAH -->
            <div class="result-card">

                <div class="card-title">

                    <i class="fa-solid fa-list-check"></i>

                    <h4>
                        Langkah Selanjutnya
                    </h4>

                </div>

                <div class="step-list">

                    <div class="step-item">

                        <div class="step-icon">
                            <i class="fa-solid fa-file"></i>
                        </div>

                        <div>
                            <h5>
                                Lakukan Daftar Ulang
                            </h5>

                            <p>
                                Lengkapi proses administrasi dan pembayaran
                            </p>
                        </div>

                    </div>

                    <div class="step-item">

                        <div class="step-icon">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>

                        <div>
                            <h5>
                                Pelunasan Biaya
                            </h5>

                            <p>
                                Bayar biaya daftar ulang sesuai ketentuan
                            </p>
                        </div>

                    </div>

                    <div class="step-item">

                        <div class="step-icon">
                            <i class="fa-solid fa-school"></i>
                        </div>

                        <div>
                            <h5>
                                Masa Orientasi Santri
                            </h5>

                            <p>
                                Ikuti kegiatan orientasi sesuai jadwal
                            </p>
                        </div>

                    </div>

                </div>

                <!-- BUTTON -->
                <div class="result-action">

                    <a href="{{ route('cek-pendaftaran') }}" class="btn-result secondary">

                        Cari Lagi

                    </a>

                    @if ($statusDiterima)
                    <a href="{{ route('daftar-ulang', $pendaftaran->id) }}" class="btn-result primary">

                        <i class="fa-solid fa-pen"></i>

                        Daftar Ulang

                    </a>
                    @endif

                </div>

            </div>

        </div>

    </section>
    @include('components.footer')
@endsection
