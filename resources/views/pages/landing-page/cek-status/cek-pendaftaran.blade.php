@extends('layout.app')

@include('components.header')

@section('content')
    <section class="cek-pendaftaran">

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

            <!-- CARD -->
            <div class="cek-card">

                <!-- ICON -->
                <div class="cek-icon">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </div>

                <!-- TITLE -->
                <div class="cek-title">

                    <h3>
                        Cek Status Pendaftaran
                    </h3>

                    <p>
                        Masukkan Nama Lengkap dan NISN untuk melihat status pendaftaran Anda
                    </p>

                </div>

                <!-- FORM -->
                <form action="{{ route('cek-pendaftaran.cari') }}" method="POST">

                    @csrf

                    @if ($errors->has('cek_status'))
                        <div class="info-box" style="border-color: #ef4444; color: #991b1b;">
                            {{ $errors->first('cek_status') }}
                        </div>
                    @endif

                    <div class="form-group">

                        <label>
                            Nama Lengkap *
                        </label>

                        <input
                            type="text"
                            name="nama_lengkap"
                            value="{{ old('nama_lengkap') }}"
                            placeholder="Masukkan nama lengkap sesuai akta kelahiran"
                            required
                        >

                        @error('nama_lengkap')
                            <small style="color: #991b1b;">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label>
                            NISN (Nomor Induk Siswa Nasional) *
                        </label>

                        <input
                            type="text"
                            name="nisn"
                            value="{{ old('nisn') }}"
                            placeholder="Masukkan 10 digit NISN"
                            required
                        >

                        @error('nisn')
                            <small style="color: #991b1b;">{{ $message }}</small>
                        @enderror

                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn-cek">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        Cek Status Pendaftaran

                    </button>

                </form>

                <!-- INFO -->
                <div class="info-box">

                    <h5>
                        Catatan: NISN dapat ditemukan di:
                    </h5>

                    <ul>

                        <li>
                            Ijazah atau SKHUN sekolah sebelumnya
                        </li>

                        <li>
                            Kartu pelajar
                        </li>

                        <li>
                            Aplikasi Dapodik sekolah
                        </li>

                        <li>
                            Website resmi
                            <a href="#">
                                https://nisn.data.kemdikbud.go.id
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- FOOTER -->
                <div class="cek-footer">

                    Belum mendaftar?

                    <a href="{{ route('informasi-pendaftaran') }}" class="btn-cek secondary">
                        Daftar Sekarang
                    </a>

                </div>

            </div>

        </div>

    </section>
    @include('components.footer')
@endsection
