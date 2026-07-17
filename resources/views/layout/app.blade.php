<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Profile-PPTU')</title>
    {{-- <link rel="icon" type="images/icon" href="{{ asset('images/icon.png') }}"> --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @php
        $files = File::glob(public_path('build/assets/*.css'));
    @endphp

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- <!-- Marzipano -->
    <link rel="stylesheet"href="https://www.marzipano.net/demos/vendor/marzipano.css"> --}}

</head>

<body>
    @include('components.flash-alert')
    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Marzipano -->
    <script src="https://cdn.jsdelivr.net/npm/marzipano@0.10.2/dist/marzipano.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @stack('script')

    {{-- <!-- html2pdf.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script> --}}

    {{-- Modal Promosi Pendaftaran --}}
    @if($showModal ?? false)
        <div class="modal fade promosi" id="promoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content promo-content">
                    <div class="promo-header">
                        <span class="badge-special">PENAWARAN SPESIAL!</span>
                        <h2 class="promo-title">{{ $periodeAktif?->nama_periode ?? 'Pendaftaran Santri Baru' }} Telah Dibuka</h2>
                        <div class="gift-icon-bg"><i class="fa-solid fa-gift"></i></div>
                    </div>
                    <div class="modal-body text-center">
                        <div class="promo-main-info">
                            <i class="fa-solid fa-gift"></i>
                            <div class="text-info">
                                <h3>{{ $promos?->nama_promo}}</h3>
                                <p>Promo pendaftaran tersedia selama periode aktif</p>
                            </div>
                        </div>

                        <div class="promo-limit-box">
                            <p>
                                Berlaku untuk 
                                @if($promos?->kuota)
                                    <strong>{{ $promos->kuota }} pendaftar pertama</strong>
                                @else
                                    <strong>semua pendaftar</strong>
                                @endif
                            </p>
                            <small>Segera daftarkan putra putri Anda dan nikmati keuntungan promo yang tersedia.</small>
                        </div>
                        <div class="row mt-4 g-0 timeline-container">
                            <div class="col-3 timeline-item">
                                <div class="timeline-icon">1</div>
                                <div class="item-card-timeline">
                                    <strong>Pendaaftaran</strong>
                                    <span>13 Mei - 30 Juni</span>
                                </div>
                            </div>
                            <div class="col-3 timeline-item">
                                <div class="timeline-icon">2</div>
                                <div class="item-card-timeline">
                                    <strong>Tes Seleksi</strong>
                                    <span>20 Juni</span>
                                </div>
                            </div>
                            <div class="col-3 timeline-item">
                                <div class="timeline-icon">3</div>
                                <div class="item-card-timeline">
                                    <strong>Pengumuman</strong>
                                    <span>25 Juni</span>
                                </div>
                            </div>
                            <div class="col-3 timeline-item">
                                <div class="timeline-icon">3</div>
                                <div class="item-card-timeline">
                                    <strong>Daftar Ulang</strong>
                                    <span>26 Juni</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="promo-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Nanti
                            Saja</button>
                        <a href="{{ route('informasi-pendaftaran') }}" class="btn btn-gold">Lihat Info Pendaftaran</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const modal = new bootstrap.Modal(
                document.getElementById('promoModal')
            );

            modal.show();
        </script>
    @endif

    {{-- Modal Tambah Berita --}}
    <div class="modal fade admin-modal" id="modalTambahBerita" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data"
                id="formTambahBerita" novalidate class="modal-content">
                @csrf
                <!-- HEADER -->
                <div class="modal-header">
                    <div class="modal-title-wrap">
                        <span>Management Berita</span>
                        <h3>Tambah Berita Baru</h3>
                    </div>
                    <button type="button" class="close-modal" data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <!-- BODY -->
                <div class="modal-body">
                    <div class="form-berita">
                        <input type="hidden" name="existing_thumbnail" id="existingThumbnail" value="">

                        <div class="left-content">
                            <!-- THUMBNAIL -->
                            <div class="upload-card">
                                <div class="card-head">
                                    <h4>Thumbnail Berita</h4>
                                    <p>
                                        Gambar utama yang tampil
                                        di landing page
                                    </p>
                                </div>
                                <label class="upload-area">
                                    <input type="file" hidden name="thumbnail" accept="image/*">
                                    <div class="upload-placeholder">
                                        <i class="fa-regular fa-image"></i>
                                        <h5>Upload Thumbnail</h5>
                                        <span>
                                            PNG / JPG maksimal 2MB
                                        </span>
                                    </div>
                                </label>
                            </div>
                            <!-- DETAIL IMAGE -->
                            <div class="upload-card">
                                <div class="card-head">
                                    <h4>Gambar Detail Berita</h4>
                                    <p>
                                        Gambar tambahan untuk halaman detail
                                    </p>
                                </div>
                                <label class="upload-area small">
                                    <input type="file" hidden name="gambar_detail_1" accept="image/*">
                                    <div class="upload-placeholder">
                                        <i class="fa-regular fa-image"></i>
                                        <h5>Upload Gambar Detail 1</h5>
                                    </div>
                                </label>

                                <label class="upload-area small mt-3">
                                    <input type="file" hidden name="gambar_detail_2" accept="image/*">
                                    <div class="upload-placeholder">
                                        <i class="fa-regular fa-image"></i>
                                        <h5>Upload Gambar Detail 2</h5>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <!-- RIGHT -->
                        <div class="right-content">
                            <!-- JUDUL -->
                            <div class="form-group">
                                <label>Judul Berita</label>
                                <input type="text" name="judul" placeholder="Masukkan judul berita">
                            </div>

                            <!-- DOUBLE -->
                            <div class="double-grid">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="kategori">
                                        <option value="Pendaftaran">Pendaftaran</option>
                                        <option value="Prestasi">Prestasi</option>
                                        <option value="Kegiatan">Kegiatan</option>
                                        <option value="Pengumuman">Pengumuman</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status">
                                        <option value="Publish">Publish</option>
                                        <option value="Draft">Draft</option>
                                    </select>
                                </div>
                            </div>

                            <div class="double-grid">
                                <div class="form-group">
                                    <label>Penulis</label>
                                    <input type="text" name="penulis" placeholder="Contoh: Admin"
                                        value="{{ session('admin')['name'] ?? 'Admin' }}">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Publish</label>
                                    <input type="date" name="tanggal_publish" value="{{ now()->toDateString() }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Blockquote</label>
                                <input type="text" name="blockquote" placeholder="Tulis kutipan penting...">
                            </div>

                            <!-- ISI -->
                            <div class="form-group">
                                <label>Isi Berita</label>
                                <textarea rows="12" name="isi_berita" placeholder="Tulis isi berita lengkap..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn-save" form="formTambahBerita" type="submit">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Berita
                    </button>
                </div>
            </form>
        </div>
    </div>

    @isset($beritas)
        <!-- MODAL VIEW -->
        @foreach ($beritas as $berita)
            <div class="modal fade admin-modal preview-modal" id="modalViewBerita{{ $berita->id }}" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="preview-top">
                                <span class="badge-category">
                                    {{ $berita->kategori }}
                                </span>
                                <div class="preview-meta">
                                    <span>
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($berita->tanggal_publish)->translatedFormat('d F Y') }}
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-user"></i>
                                        {{ $berita->penulis }}
                                    </span>
                                </div>
                            </div>
                            <button type="button" class="close-modal" data-dismiss="modal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="preview-thumbnail">
                                <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="Thumbnail Berita">
                                <div class="overlay"></div>
                                <div class="title-wrap">
                                    <h2>
                                        {{ $berita->judul }}
                                    </h2>
                                </div>
                            </div>
                            @php
                                $paragraphs = array_filter(preg_split('/\r\n|\r|\n/', $berita->isi_berita));

                                $half = ceil(count($paragraphs) / 2);
                            @endphp
                            <div class="preview-content">
                                @foreach (array_slice($paragraphs, 0, $half) as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                                @if ($berita->blockquote)
                                    <blockquote>
                                        “{{ $berita->blockquote }}”
                                    </blockquote>
                                @endif
                                @foreach (array_slice($paragraphs, $half) as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                            <!-- DETAIL IMAGE -->
                            <div class="preview-gallery">
                                <div class="gallery-head">
                                    <span>Dokumentasi {{ $berita->judul }}</span>
                                </div>
                                <div class="gallery-grid">
                                    <div class="gallery-item">
                                        <img src="{{ asset('storage/' . $berita->gambar_detail_1) }}"
                                            alt="Gambar Detail 1">
                                    </div>
                                    <div class="gallery-item">
                                        <img src="{{ asset('storage/' . $berita->gambar_detail_2) }}"
                                            alt="Gambar Detail 2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button class="btn-cancel" data-dismiss="modal">

                                Tutup

                            </button>

                            <button class="btn-save" data-dismiss="modal" data-toggle="modal"
                                data-target="#modalEditBerita">

                                <i class="fa-regular fa-pen-to-square"></i>
                                Edit Berita

                            </button>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endisset

    @isset($beritas)
        {{-- Modal Edit Berita --}}
        @foreach ($beritas as $berita)
            <div class="modal fade admin-modal" id="modalEditBerita{{ $berita->id }}" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <form action="{{ route('berita.update', $berita->id) }}" method="POST"
                        enctype="multipart/form-data" id="formEditBerita" novalidate class="modal-content">
                        @csrf
                        <!-- HEADER -->
                        <div class="modal-header">
                            <div class="modal-title-wrap">
                                <span>Management Berita</span>
                                <h3>Edit Berita</h3>
                            </div>
                            <button type="button" class="close-modal" data-dismiss="modal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <!-- BODY -->
                        <div class="modal-body">
                            <div class="form-berita">
                                <!-- LEFT -->
                                <div class="left-content">
                                    <!-- THUMBNAIL -->
                                    <div class="upload-card">
                                        <div class="card-head">
                                            <h4>Thumbnail Berita</h4>
                                            <p>
                                                Gambar utama yang tampil
                                                di landing page
                                            </p>
                                        </div>
                                        <!-- PREVIEW -->
                                        <div class="preview-image">
                                            <img src="{{ asset('storage/' . $berita->thumbnail) }}"
                                                alt="Thumbnail Berita">
                                            <div class="preview-overlay">
                                                <label class="change-image">
                                                    <input type="file" name="thumbnail" accept="image/*" hidden>
                                                    <i class="fa-solid fa-camera"></i>
                                                    Ganti Thumbnail
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- DETAIL IMAGE -->
                                    <div class="upload-card">
                                        <div class="card-head">
                                            <h4>Gambar Detail Berita</h4>
                                            <p>
                                                Gambar tambahan halaman detail berita
                                            </p>
                                        </div>
                                        <!-- DETAIL PREVIEW -->
                                        <div class="detail-gallery">
                                            <div class="detail-item">
                                                <img src="{{ asset('storage/' . $berita->gambar_detail_1) }}"
                                                    alt="Gambar Detail 1">
                                                <button class="remove-image">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- ADD IMAGE -->
                                        <label class="upload-area small mt-3">
                                            <input type="file" name="gambar_detail_1" accept="image/*" hidden>
                                            <div class="upload-placeholder">
                                                <i class="fa-regular fa-images"></i>
                                                <h5>Tambah Gambar</h5>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <!-- RIGHT -->
                                <div class="right-content">
                                    <!-- TITLE -->
                                    <div class="form-group">
                                        <label>Judul Berita</label>
                                        <input type="text" name="judul" value="{{ $berita->judul }}">
                                    </div>
                                    <!-- DOUBLE -->
                                    <div class="double-grid">
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select name="kategori">
                                                <option value="Pendaftaran"
                                                    {{ $berita->kategori === 'Pendaftaran' ? 'selected' : '' }}>
                                                    Pendaftaran
                                                </option>
                                                <option value="Kegiatan"
                                                    {{ $berita->kategori === 'Kegiatan' ? 'selected' : '' }}>
                                                    Kegiatan
                                                </option>
                                                <option value="Prestasi"
                                                    {{ $berita->kategori === 'Prestasi' ? 'selected' : '' }}>
                                                    Prestasi
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status">
                                                <option value="Publish"
                                                    {{ $berita->status === 'Publish' ? 'selected' : '' }}>
                                                    Publish
                                                </option>
                                                <option value="Draft"
                                                    {{ $berita->status === 'Draft' ? 'selected' : '' }}>
                                                    Draft
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Penulis</label>
                                        <input type="text" name="penulis" value="{{ $berita->penulis }}"
                                            placeholder="Contoh: Admin">
                                    </div>
                                    <div class="form-group">
                                        <label>Blockquote</label>
                                        <input type="text" name="blockquote" value="{{ $berita->blockquote }}"
                                            placeholder="Tulis kutipan penting...">
                                    </div>
                                    <!-- CONTENT -->
                                    <div class="form-group">
                                        <label>Isi Berita</label>
                                        <textarea name="isi_berita" rows="14">{{ $berita->isi_berita }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FOOTER -->
                        <div class="modal-footer">
                            <button class="btn-cancel" data-dismiss="modal">
                                Batal
                            </button>
                            <button class="btn-save">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan Perubahan
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endisset

    @isset($beritas)
        <!-- HAPUS MODAL BERITA -->
        @foreach ($beritas as $berita)
            <div class="modal fade delete-modal" id="modalHapusBerita{{ $berita->id }}" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content" action="{{ route('berita.destroy', $berita->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <!-- ICON -->
                        <div class="delete-icon">
                            <i class="fa-regular fa-trash-can"></i>
                        </div>
                        <!-- CONTENT -->
                        <div class="delete-content">
                            <span class="delete-label">
                                Konfirmasi Penghapusan
                            </span>
                            <h3>
                                Yakin ingin menghapus data ini?
                            </h3>
                            <p>
                                Data yang sudah dihapus tidak dapat dikembalikan lagi.
                                Pastikan tindakan ini sudah benar.
                            </p>

                        </div>
                        <!-- ACTION -->
                        <div class="delete-action">
                            <button type="button" class="btn-cancel" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn-delete-confirm">
                                <i class="fa-solid fa-trash"></i>
                                Hapus Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endisset

    {{-- MODAL TAMBAH GALERI --}}
    <div class="modal fade admin-modal" id="modalTambahGaleri" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                {{-- HEADER --}}
                <div class="modal-header">
                    <div>
                        <span class="modal-label">
                            Tambah Data
                        </span>
                        <h3>
                            Tambah Galeri Baru
                        </h3>
                    </div>
                    <button type="button" class="close-modal" data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                {{-- BODY --}}
                <div class="modal-body">
                    <form action="{{ route('galeri.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            {{-- JUDUL --}}
                            <div class="form-group full">
                                <label>
                                    Judul Galeri
                                </label>
                                <input type="text" name="judul" placeholder="Masukkan judul galeri">
                            </div>
                            {{-- THUMBNAIL --}}
                            <div class="form-group">
                                <label>
                                    Thumbnail Utama
                                </label>
                                <div class="upload-box">
                                    <input type="file" name="thumbnail">
                                    <div class="upload-placeholder">
                                        <i class="fa-regular fa-image"></i>
                                        <span>
                                            Upload thumbnail
                                        </span>
                                    </div>
                                </div>
                            </div>
                            {{-- FOTO --}}
                            <div class="form-group">
                                <label>
                                    Upload Foto Galeri
                                </label>
                                <div class="upload-box multiple">
                                    <input type="file" multiple name="fotos[]">
                                    <div class="upload-placeholder">
                                        <i class="fa-regular fa-images"></i>
                                        <span>
                                            Upload banyak foto
                                        </span>
                                    </div>
                                </div>
                            </div>
                            {{-- TANGGAL --}}
                            <div class="form-group">
                                <label>
                                    Tanggal Kegiatan
                                </label>
                                <input type="date" name="tanggal_kegiatan">
                            </div>
                            {{-- STATUS --}}
                            <div class="form-group">
                                <label>
                                    Status Publish
                                </label>
                                <select name="status">
                                    <option value="Publish">Publish</option>
                                    <option value="Draft" selected>Draft</option>
                                </select>
                            </div>
                            {{-- DESKRIPSI --}}
                            <div class="form-group full">
                                <label>
                                    Deskripsi
                                </label>
                                <textarea rows="5" name="deskripsi" placeholder="Tulis deskripsi galeri..."></textarea>
                            </div>
                        </div>
                        {{-- FOOTER --}}
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn-save">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan Galeri
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @isset($galeris)
        {{-- MODAL EDIT GALERI --}}
        @foreach ($galeris as $galeri)
            <div class="modal fade admin-modal" id="modalEditGaleri{{ $galeri->id }}"tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        {{-- HEADER --}}
                        <div class="modal-header">
                            <div>
                                <span class="modal-label">
                                    Edit Data
                                </span>
                                <h3>
                                    Edit Galeri
                                </h3>
                            </div>
                            <button type="button" class="close-modal" data-dismiss="modal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        {{-- BODY --}}
                        <div class="modal-body">
                            <form action="{{ route('galeri.update', $galeri->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-grid">
                                    {{-- JUDUL --}}
                                    <div class="form-group full">
                                        <label>
                                            Judul Galeri
                                        </label>
                                        <input type="text" name="judul" value="{{ $galeri->judul }}">
                                    </div>
                                    {{-- THUMBNAIL --}}
                                    <div class="form-group">
                                        <label>
                                            Thumbnail Utama
                                        </label>
                                        <div class="upload-preview">
                                            <img src="{{ asset('storage/' . $galeri->thumbnail) }}" alt="Thumbnail">
                                            <div class="overlay-upload">
                                                <i class="fa-solid fa-camera"></i>
                                                Ganti Thumbnail
                                            </div>
                                            <input type="file" name="thumbnail">
                                        </div>
                                    </div>
                                    {{-- FOTO GALERI --}}
                                    <div class="form-group">
                                        <label>
                                            Tambah Foto Baru
                                        </label>
                                        <div class="upload-box multiple">
                                            <input type="file" multiple name="fotos[]">
                                            <div class="upload-placeholder">
                                                <i class="fa-regular fa-images"></i>
                                                <span>
                                                    Upload foto tambahan
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- TANGGAL --}}
                                    <div class="form-group">
                                        <label>
                                            Tanggal Kegiatan
                                        </label>
                                        <input type="date" name="tanggal_kegiatan"
                                            value="{{ $galeri->tanggal_kegiatan->format('Y-m-d') }}">
                                    </div>
                                    {{-- STATUS --}}
                                    <div class="form-group">
                                        <label>
                                            Status Publish
                                        </label>
                                        <select name="status">
                                            <option value="Publish" {{ $galeri->status === 'Publish' ? 'selected' : '' }}>
                                                Publish
                                            </option>
                                            <option value="Draft" {{ $galeri->status === 'Draft' ? 'selected' : '' }}>
                                                Draft
                                            </option>
                                        </select>
                                    </div>
                                    {{-- DESKRIPSI --}}
                                    <div class="form-group full">
                                        <label>
                                            Deskripsi
                                        </label>
                                        <textarea rows="5" name="deskripsi">{{ $galeri->deskripsi }}</textarea>
                                    </div>
                                </div>
                                {{-- FOTO YANG SUDAH ADA --}}
                                <div class="existing-gallery">
                                    <div class="section-head">
                                        <h4>
                                            Foto Dalam Galeri
                                        </h4>
                                        <span>
                                            {{ $galeri->fotos->count() }} Foto
                                        </span>
                                    </div>
                                    <div class="gallery-preview-grid">
                                        @foreach ($galeri->fotos as $foto)
                                            <div class="gallery-preview-item">
                                                <img src="{{ asset('storage/' . $foto->gambar) }}" alt="">
                                                <button type="button" class="delete-photo"
                                                    onclick="markDelete(this, {{ $foto->id }})">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                {{-- FOOTER --}}
                                <div class="modal-footer">
                                    <button type="button" class="btn-cancel" data-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn-save">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                let deletedPhotos = [];

                function markDelete(btn, id) {

                    // toggle delete
                    if (deletedPhotos.includes(id)) {
                        deletedPhotos = deletedPhotos.filter(x => x !== id);
                        btn.parentElement.style.opacity = "1";
                    } else {
                        deletedPhotos.push(id);
                        btn.parentElement.style.opacity = "0.4";
                    }

                    document.getElementById('hapus_foto').value = JSON.stringify(deletedPhotos);
                }
            </script>
        @endforeach
    @endisset

    @isset($galeris)
    {{-- MODAL VIEW GALERI --}}
        @foreach($galeris as $galeri)
            <div class="modal fade admin-modal" id="modalViewGaleri{{ $galeri->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        {{-- HEADER --}}
                        <div class="modal-header">
                            <div>
                                <span class="modal-label">
                                    Detail Galeri
                                </span>
                                <h3>
                                    {{ $galeri->judul }}
                                </h3>
                            </div>
                            <button type="button" class="close-modal" data-dismiss="modal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        {{-- BODY --}}
                        <div class="modal-body">
                            {{-- HERO --}}
                            <div class="view-gallery-hero">
                                <img src="{{ asset('storage/' . $galeri->thumbnail) }}" alt="Thumbnail Galeri">
                                <div class="overlay">
                                    <div class="gallery-info">
                                        <span class="badge-status">
                                            {{ ucfirst($galeri->status) }}
                                        </span>
                                        <h2>
                                            {{ $galeri->judul }}
                                        </h2>
                                        <div class="meta">
                                            <span>
                                                <i class="fa-regular fa-calendar"></i>
                                                {{ \Carbon\Carbon::parse($galeri->created_at)->format('d M Y') }}
                                            </span>
                                            <span>
                                                <i class="fa-regular fa-images"></i>
                                                {{ $galeri->fotos->count() }} Foto
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="view-description">
                                <h4>
                                    Deskripsi Kegiatan
                                </h4>
                                <p>
                                    {{ $galeri->deskripsi }}
                                </p>
                            </div>
                            <div class="view-gallery-section">
                                <div class="section-head">
                                    <h4>
                                        Foto Dokumentasi
                                    </h4>
                                    <span>
                                        Total {{ $galeri->fotos->count() }} Foto
                                    </span>
                                </div>
                                <div class="view-gallery-grid">
                                    @foreach($galeri->fotos as $foto)
                                        <div class="view-gallery-item">
                                            <img src="{{ asset('storage/' . $foto->gambar) }}" alt="">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endisset

    @isset($galeris)
    <!-- HAPUS MODAL GALERI -->
        @foreach($galeris as $galeri)
            <div class="modal fade delete-modal" id="modalHapusGaleri{{ $galeri->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content" action="{{ route('galeri.destroy', $galeri->id) }}" method="POST">
                        @csrf
                        @method('DELETE')>
                        <!-- ICON -->
                        <div class="delete-icon">
                            <i class="fa-regular fa-trash-can"></i>
                        </div>
                        <!-- CONTENT -->
                        <div class="delete-content">
                            <span class="delete-label">
                                Konfirmasi Penghapusan
                            </span>
                            <h3>
                                Yakin ingin menghapus data ini?
                            </h3>
                            <p>
                                Data yang sudah dihapus tidak dapat dikembalikan lagi.
                                Pastikan tindakan ini sudah benar.
                            </p>
                        </div>
                        <!-- ACTION -->
                        <div class="delete-action">
                            <button type="button" class="btn-cancel" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn-delete-confirm">
                                <i class="fa-solid fa-trash"></i>
                                Hapus Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endisset

    {{-- MODAL TAMBAH GURU --}}
    <div class="modal fade admin-modal" id="modalTambahGuru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                {{-- HEADER --}}
                <div class="modal-header">
                    <div>
                        <span class="modal-label">
                            Tambah Data
                        </span>
                        <h3>
                            Tambah Guru & Ustadz
                        </h3>
                    </div>
                    <button type="button" class="close-modal" data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                {{-- BODY --}}
                <div class="modal-body">
                @if ($errors->any())
                        <div class="alert alert-danger m-3"> <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('guru.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            {{-- FOTO --}}
                            <div class="form-group full">
                                <label>
                                    Foto Guru / Ustadz
                                </label>
                                <div class="guru-upload">
                                    <input type="file" name="foto">
                                    <div class="upload-content">
                                        <div class="upload-icon">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                        </div>
                                        <h4>
                                            Upload Foto
                                        </h4>
                                        <p>
                                            PNG, JPG atau JPEG
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>
                                    Nama Lengkap
                                </label>
                                <input type="text" placeholder="Masukkan nama guru" name="nama_lengkap">
                            </div>
                            <div class="form-group">
                                <label>
                                    Kategori
                                </label>
                                <select name="kategori">
                                    <option selected disabled>
                                        Pilih Kategori
                                    </option>
                                    @foreach($kategoriGuru ?? [] as $kategori)
                                        <option value="{{ $kategori }}">
                                            {{ $kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    Status
                                </label>
                                <select name="status">
                                    <option>
                                        Aktif
                                    </option>
                                    <option>
                                        Nonaktif
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    Mapel / Bidang
                                </label>
                                <input type="text" placeholder="Contoh: Nahwu & Fiqih" name="mapel_bidang">
                            </div>
                            <div class="form-group">
                                <label>
                                    Pendidikan
                                </label>
                                <input type="text" placeholder="Contoh: Alumni Sidogiri" name="pendidikan">
                            </div>
                            <div class="form-group">
                                <label>
                                    Alanat
                                </label>
                                <textarea row="5" type="text" placeholder="Contoh: RT/RW Desa Kesamatan Kabupaten" name="alamat"></textarea>
                            </div>
                        </div>
                        {{-- FOOTER --}}
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn-save">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @isset($gurus)
    {{-- MODAL EDIT GURU --}}
        @foreach($gurus as $guru)
            <div class="modal fade admin-modal"
                id="modalEditGuru{{$guru->id}}"
                tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        {{-- HEADER --}}
                        <div class="modal-header">
                            <div>
                                <span class="modal-label">
                                    Edit Data
                                </span>
                                <h3>
                                    Edit Guru & Ustadz
                                </h3>
                            </div>
                            <button type="button"
                                class="close-modal"
                                data-dismiss="modal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        {{-- BODY --}}
                        <div class="modal-body">
                            <form action="{{ route('guru.update', $guru->id) }}"
                                method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-grid">
                                    {{-- FOTO --}}
                                    <div class="form-group full">
                                        <label>
                                            Foto Guru / Ustadz
                                        </label>
                                        <div class="guru-upload edit">
                                            <img src="{{ asset('storage/' . $guru->foto) }}"
                                                alt="Foto Guru">
                                            <input type="file" name="foto">
                                            <div class="upload-overlay">
                                                <div class="overlay-content">
                                                    <i class="fa-solid fa-camera"></i>
                                                    <span>
                                                        Ganti Foto
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- NAMA --}}
                                    <div class="form-group">
                                        <label>
                                            Nama Lengkap
                                        </label>
                                        <input type="text"
                                            name="nama_lengkap"
                                            value="{{ $guru->nama_lengkap }}">
                                    </div>
                                    {{-- KATEGORI --}}
                                    <div class="form-group">
                                        <label>
                                            Kategori
                                        </label>
                                        <select name="kategori">
                                            @foreach($kategoriGuru ?? [] as $kategori)
                                                <option value="{{ $kategori }}"
                                                    {{ $guru->kategori == $kategori ? 'selected' : '' }}>
                                                    {{ $kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- MAPEL --}}
                                    <div class="form-group">
                                        <label>
                                            Mapel / Bidang
                                        </label>
                                        <input type="text"
                                            name="mapel_bidang"
                                            value="{{ $guru->mapel_bidang }}">
                                    </div>
                                    {{-- STATUS --}}
                                    <div class="form-group">
                                        <label>
                                            Status
                                        </label>
                                        <select name="status">
                                            <option value="Aktif"
                                                {{ $guru->status == 'Aktif' ? 'selected' : '' }}>
                                                Aktif
                                            </option>
                                            <option value="Nonaktif"
                                                {{ $guru->status == 'Nonaktif' ? 'selected' : '' }}>
                                                Nonaktif
                                            </option>
                                        </select>
                                    </div>
                                    {{-- PENDIDIKAN --}}
                                    <div class="form-group">
                                        <label>
                                            Pendidikan
                                        </label>
                                        <input type="text"
                                            name="pendidikan"
                                            value="{{ $guru->pendidikan }}">
                                    </div>
                                    {{-- ALAMAT --}}
                                    <div class="form-group full">
                                        <label>
                                            Alamat
                                        </label>
                                        <textarea name="alamat"
                                            rows="4">{{ $guru->alamat }}</textarea>
                                    </div>
                                </div>
                                {{-- FOOTER --}}
                                <div class="modal-footer">
                                    <button type="button"
                                        class="btn-cancel"
                                        data-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="btn-save">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endisset

    @isset($gurus)
    {{-- MODAL VIEW GURU --}}
        @foreach($gurus as $guru)
            <div class="modal fade admin-modal" id="modalViewGuru{{$guru->id}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content guru-view-modal">
                        {{-- HEADER --}}
                        <div class="modal-header">
                            <button type="button" class="close-modal" data-dismiss="modal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <div class="guru-view-image">
                                <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto Guru">
                                <div class="image-overlay"></div>
                            </div>
                        </div>
                        {{-- BODY --}}
                        <div class="modal-body">
                            {{-- TITLE --}}
                            <h2>
                                {{$guru->nama_lengkap}}
                            </h2>
                            <p class="guru-mapel">
                                {{$guru->kategori}}
                            </p>
                            {{-- META --}}
                            <div class="guru-meta">
                                <div class="meta-item">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    <span>
                                        {{$guru->pendidikan}}
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <i class="fa-solid fa-book-open"></i>
                                    <span>
                                        {{$guru->mapel_bidang}}
                                    </span>
                                </div>
                            </div>
                        </div>
                        {{-- FOOTER --}}
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" data-dismiss="modal">
                                Tutup
                            </button>
                            <button type="button" class="btn-save" data-dismiss="modal" data-toggle="modal"
                                data-target="#modalEditGuru">
                                <i class="fa-solid fa-pen"></i>
                                Edit Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endisset

    @isset($gurus)
    <!-- HAPUS MODAL GURU -->
        @foreach($gurus as $guru)
            <div class="modal fade delete-modal" id="modalHapusGuru{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content" action="{{ route('guru.destroy', $guru->id) }}" method="POST">
                        @csrf
                        @method('DELETE')>
                        <!-- ICON -->
                        <div class="delete-icon">
                            <i class="fa-regular fa-trash-can"></i>
                        </div>
                        <!-- CONTENT -->
                        <div class="delete-content">
                            <span class="delete-label">
                                Konfirmasi Penghapusan
                            </span>
                            <h3>
                                Yakin ingin menghapus data ini?
                            </h3>
                            <p>
                                Data yang sudah dihapus tidak dapat dikembalikan lagi.
                                Pastikan tindakan ini sudah benar.
                            </p>
                        </div>
                        <!-- ACTION -->
                        <div class="delete-action">
                            <button type="button" class="btn-cancel" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn-delete-confirm">
                                <i class="fa-solid fa-trash"></i>
                                Hapus Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endisset

    <!-- ===================================================== -->
    <!-- MODAL EDIT PENDAFTAR -->
    <!-- ===================================================== -->
    @if(isset($pendaftarans) && ($canManageSelectedPeriod ?? true))
        @foreach($pendaftarans as $pendaftaran)
            <div class="modal fade admin-modal" id="modalEditPendaftaran{{$pendaftaran->id}}" tabindex="-1">
                <div class=
                "modal-dialog modal-xxl modal-dialog-scrollable modal-dialog-centered">
                    <form action="{{ route('pendaftaran.update', $pendaftaran->id) }}"
                        method="POST"
                        class="modal-content pendaftaran"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- HEADER -->
                        <div class="modal-header">
                            <div class="modal-title-wrap">
                                <h3>Edit Pendaftaran Santri</h3>
                                <span>Edit Data</span>
                            </div>

                            <button type="button" class="close-modal" data-dismiss="modal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <!-- BODY -->
                        <div class="modal-body">

                            <!-- IDENTITAS -->
                            <div class="admin-form-section">
                                <div class="section-title">
                                    <i class="fa-solid fa-user"></i>
                                    <div>
                                        <h4>Identitas Santri</h4>
                                        <p>Informasi dasar calon santri</p>
                                    </div>
                                </div>

                                <div class="admin-form-grid">

                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input name="nama_lengkap"
                                            type="text"
                                            value="{{ $pendaftaran->nama_lengkap }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Nama Panggilan</label>
                                        <input name="nama_panggilan"
                                            type="text"
                                            value="{{ $pendaftaran->nama_panggilan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>

                                        <select name="jenis_kelamin">
                                            <option value="">Pilih</option>

                                            <option value="L"
                                                {{ $pendaftaran->jenis_kelamin == 'L' ? 'selected' : '' }}>
                                                Laki-laki
                                            </option>

                                            <option value="P"
                                                {{ $pendaftaran->jenis_kelamin == 'P' ? 'selected' : '' }}>
                                                Perempuan
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Agama</label>

                                        <select name="agama">
                                            <option value="">Pilih</option>

                                            <option value="Islam"
                                                {{ $pendaftaran->agama == 'Islam' ? 'selected' : '' }}>
                                                Islam
                                            </option>

                                            <option value="Kristen"
                                                {{ $pendaftaran->agama == 'Kristen' ? 'selected' : '' }}>
                                                Kristen
                                            </option>

                                            <option value="Katolik"
                                                {{ $pendaftaran->agama == 'Katolik' ? 'selected' : '' }}>
                                                Katolik
                                            </option>

                                            <option value="Hindu"
                                                {{ $pendaftaran->agama == 'Hindu' ? 'selected' : '' }}>
                                                Hindu
                                            </option>

                                            <option value="Budha"
                                                {{ $pendaftaran->agama == 'Budha' ? 'selected' : '' }}>
                                                Budha
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Tempat Lahir</label>
                                        <input name="tempat_lahir"
                                            type="text"
                                            value="{{ $pendaftaran->tempat_lahir }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input name="tanggal_lahir"
                                            type="date"
                                            value="{{ $pendaftaran->tanggal_lahir }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Kewarganegaraan</label>
                                        <input name="kewarganegaraan"
                                            type="text"
                                            value="{{ $pendaftaran->kewarganegaraan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Anak Ke</label>
                                        <input name="anak_ke"
                                            type="number"
                                            value="{{ $pendaftaran->anak_ke }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah Saudara Kandung</label>
                                        <input name="jumlah_saudara_kandung"
                                            type="number"
                                            value="{{ $pendaftaran->jumlah_saudara_kandung }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah Saudara Angkat</label>
                                        <input name="jumlah_saudara_angkat"
                                            type="number"
                                            value="{{ $pendaftaran->jumlah_saudara_angkat }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah Saudara Tiri</label>
                                        <input name="jumlah_saudara_tiri"
                                            type="number"
                                            value="{{ $pendaftaran->jumlah_saudara_tiri }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Status Anak</label>

                                        <select name="status_anak">
                                            <option value="">Pilih</option>

                                            <option value="Lengkap"
                                                {{ $pendaftaran->status_anak == 'Lengkap' ? 'selected' : '' }}>
                                                Lengkap
                                            </option>

                                            <option value="Yatim"
                                                {{ $pendaftaran->status_anak == 'Yatim' ? 'selected' : '' }}>
                                                Yatim
                                            </option>

                                            <option value="Piatu"
                                                {{ $pendaftaran->status_anak == 'Piatu' ? 'selected' : '' }}>
                                                Piatu
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group full">
                                        <label>Bahasa Rumah</label>

                                        <input name="bahasa_rumah"
                                            type="text"
                                            value="{{ $pendaftaran->bahasa_rumah }}">
                                    </div>

                                </div>
                            </div>

                            <!-- TEMPAT TINGGAL -->
                            <div class="admin-form-section">

                                <div class="section-title">
                                    <i class="fa-solid fa-house"></i>

                                    <div>
                                        <h4>Tempat Tinggal & Kesehatan</h4>
                                        <p>Alamat dan kesehatan</p>
                                    </div>
                                </div>

                                <div class="admin-form-grid">

                                    <div class="form-group full">
                                        <label>Alamat</label>

                                        <textarea name="alamat"
                                                rows="4">{{ $pendaftaran->alamat }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>RT/RW</label>

                                        <input name="rt_rw"
                                            type="text"
                                            value="{{ $pendaftaran->rt_rw }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Desa</label>

                                        <input name="desa"
                                            type="text"
                                            value="{{ $pendaftaran->desa }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Kecamatan</label>

                                        <input name="kecamatan"
                                            type="text"
                                            value="{{ $pendaftaran->kecamatan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Kabupaten</label>

                                        <input name="kabupaten"
                                            type="text"
                                            value="{{ $pendaftaran->kabupaten }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Tempat Tinggal</label>

                                        <select name="tempat_tinggal">

                                            <option value="">Pilih</option>

                                            <option value="Pada Orang Tua"
                                                {{ $pendaftaran->tempat_tinggal == 'Pada Orang Tua' ? 'selected' : '' }}>
                                                Pada Orang Tua
                                            </option>

                                            <option value="Menumpang"
                                                {{ $pendaftaran->tempat_tinggal == 'Menumpang' ? 'selected' : '' }}>
                                                Menumpang
                                            </option>

                                            <option value="Di Asrama"
                                                {{ $pendaftaran->tempat_tinggal == 'Di Asrama' ? 'selected' : '' }}>
                                                Di Asrama
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Jarak Rumah</label>

                                        <input name="jarak_rumah"
                                            type="text"
                                            value="{{ $pendaftaran->jarak_rumah }}">
                                    </div>

                                    <div class="form-group">
                                        <label>No HP Orang Tua</label>

                                        <input name="no_hp_ortu"
                                            type="text"
                                            value="{{ $pendaftaran->no_hp_ortu }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Berat Badan</label>

                                        <input name="berat_badan"
                                            type="text"
                                            value="{{ $pendaftaran->berat_badan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Tinggi Badan</label>

                                        <input name="tinggi_badan"
                                            type="text"
                                            value="{{ $pendaftaran->tinggi_badan }}">
                                    </div>

                                    <div class="form-group full">
                                        <label>Riwayat Penyakit</label>

                                        <textarea name="riwayat_penyakit"
                                                rows="3">{{ $pendaftaran->riwayat_penyakit }}</textarea>
                                    </div>

                                    <div class="form-group full">
                                        <label>Kelainan Jasmani</label>

                                        <textarea name="kelainan_jasmani"
                                                rows="3">{{ $pendaftaran->kelainan_jasmani }}</textarea>
                                    </div>

                                </div>
                            </div>

                            <!-- PENDIDIKAN -->
                            <div class="admin-form-section">

                                <div class="section-title">
                                    <i class="fa-solid fa-school"></i>

                                    <div>
                                        <h4>Pendidikan</h4>
                                        <p>Riwayat pendidikan</p>
                                    </div>
                                </div>

                                <div class="admin-form-grid">

                                    <div class="form-group">
                                        <label>Jenjang Pendidikan</label>

                                        <input name="jenjang_pendidikan"
                                            type="text"
                                            value="{{ optional($pendaftaran->pendidikan)->jenjang_pendidikan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Jurusan</label>

                                        <input name="jurusan"
                                            type="text"
                                            value="{{ optional($pendaftaran->pendidikan)->jurusan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Sekolah Asal</label>

                                        <input name="sekolah_asal"
                                            type="text"
                                            value="{{ optional($pendaftaran->pendidikan)->sekolah_asal }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tahun Lulus</label>

                                        <input name="tahun_lulus"
                                            type="text"
                                            value="{{ optional($pendaftaran->pendidikan)->tahun_lulus }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal dan Nomor Ijazah</label>
                                        <input name="nomor_ijazah"
                                            type="text"
                                            value="{{ optional($pendaftaran->pendidikan)->tanggal_nomor_ijazah }}">
                                    </div>
                                    <div class="form-group">
                                        <label>NISN</label>
                                        <input name="nisn"
                                            type="text"
                                            value="{{ optional($pendaftaran->pendidikan)->nisn }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Lama Belajar</label>
                                        <input name="lama_belajar"
                                            type="text"
                                            value="{{ optional($pendaftaran->pendidikan)->lama_belajar }}">
                                    </div>
                                </div>
                            </div>

                            @php
                                $ayah = $pendaftaran->orangTuas->where('tipe', 'ayah')->first();
                                $ibu = $pendaftaran->orangTuas->where('tipe', 'ibu')->first();
                                $wali = $pendaftaran->orangTuas->where('tipe', 'wali')->first();
                            @endphp

                            <!-- DATA ORANG TUA -->
                            <div class="admin-form-section">

                                <div class="section-title">
                                    <i class="fa-solid fa-users"></i>

                                    <div>
                                        <h4>Data Orang Tua</h4>
                                        <p>Informasi ayah, ibu, dan wali</p>
                                    </div>
                                </div>

                                <div class="admin-form-grid">

                                    <!-- AYAH -->
                                    <div class="form-group">
                                        <label>Nama Ayah</label>

                                        <input type="text"
                                            name="nama_ayah"
                                            value="{{ optional($ayah)->nama }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Status Ayah</label>
                                        <select name="status_ayah">
                                            <option value="Masih Hidup"
                                                {{ optional($ayah)->status == 'Masih Hidup' ? 'selected' : '' }}>
                                                Masih Hidup
                                            </option>
                                            <option value="Sudah Meninggal"
                                                {{ optional($ayah)->status == 'Sudah Meninggal' ? 'selected' : '' }}>
                                                Sudah Meninggal
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Tempat Lahir Ayah</label>

                                        <input type="text"
                                            name="tempat_lahir_ayah"
                                            value="{{ optional($ayah)->tempat_lahir }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Lahir Ayah</label>

                                        <input type="date"
                                            name="tanggal_lahir_ayah"
                                            value="{{ optional($ayah)->tanggal_lahir }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Agama Ayah</label>

                                        <input type="text"
                                            name="agama_ayah"
                                            value="{{ optional($ayah)->agama }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Pendidikan Ayah</label>

                                        <input type="text"
                                            name="pendidikan_ayah"
                                            value="{{ optional($ayah)->pendidikan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Pekerjaan Ayah</label>

                                        <input type="text"
                                            name="pekerjaan_ayah"
                                            value="{{ optional($ayah)->pekerjaan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Penghasilan Ayah</label>

                                        <input type="text"
                                            name="penghasilan_ayah"
                                            value="{{ optional($ayah)->penghasilan }}">
                                    </div>

                                    <div class="form-group full">
                                        <label>Alamat Ayah</label>

                                        <textarea name="alamat_ayah"
                                                rows="3">{{ optional($ayah)->alamat }}</textarea>
                                    </div>

                                    <!-- IBU -->
                                    <div class="form-group">
                                        <label>Nama Ibu</label>

                                        <input type="text"
                                            name="nama_ibu"
                                            value="{{ optional($ibu)->nama }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Status Ibu</label>

                                        <select name="status_ibu">
                                            <option value="Masih Hidup"
                                                {{ optional($ibu)->status == 'Masih Hidup' ? 'selected' : '' }}>
                                                Masih Hidup
                                            </option>
                                            <option value="Sudah Meninggal"
                                                {{ optional($ibu)->status == 'Sudah Meninggal' ? 'selected' : '' }}>
                                                Sudah Meninggal
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Tempat Lahir Ibu</label>

                                        <input type="text"
                                            name="tempat_lahir_ibu"
                                            value="{{ optional($ibu)->tempat_lahir }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Lahir Ibu</label>

                                        <input type="date"
                                            name="tanggal_lahir_ibu"
                                            value="{{ optional($ibu)->tanggal_lahir }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Agama Ibu</label>

                                        <input type="text"
                                            name="agama_ibu"
                                            value="{{ optional($ibu)->agama }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Pendidikan Ibu</label>

                                        <input type="text"
                                            name="pendidikan_ibu"
                                            value="{{ optional($ibu)->pendidikan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Pekerjaan Ibu</label>

                                        <input type="text"
                                            name="pekerjaan_ibu"
                                            value="{{ optional($ibu)->pekerjaan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Penghasilan Ibu</label>

                                        <input type="text"
                                            name="penghasilan_ibu"
                                            value="{{ optional($ibu)->penghasilan }}">
                                    </div>

                                    <div class="form-group full">
                                        <label>Alamat Ibu</label>

                                        <textarea name="alamat_ibu"
                                                rows="3">{{ optional($ibu)->alamat }}</textarea>
                                    </div>

                                    <!-- WALI -->
                                    <div class="form-group">
                                        <label>Nama Wali</label>

                                        <input type="text"
                                            name="nama_wali"
                                            value="{{ optional($wali)->nama }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Status Wali</label>

                                        <select name="status_wali">
                                            <option value="Masih Hidup"
                                                {{ optional($wali)->status == 'Masih Hidup' ? 'selected' : '' }}>
                                                Masih Hidup
                                            </option>
                                            <option value="Sudah Meninggal"
                                                {{ optional($wali)->status == 'Sudah Meninggal' ? 'selected' : '' }}>
                                                Sudah Meninggal
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Tempat Lahir Wali</label>

                                        <input type="text"
                                            name="tempat_lahir_wali"
                                            value="{{ optional($wali)->tempat_lahir }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Lahir Wali</label>

                                        <input type="date"
                                            name="tanggal_lahir_wali"
                                            value="{{ optional($wali)->tanggal_lahir }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Agama Wali</label>

                                        <input type="text"
                                            name="agama_wali"
                                            value="{{ optional($wali)->agama }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Pendidikan Wali</label>

                                        <input type="text"
                                            name="pendidikan_wali"
                                            value="{{ optional($wali)->pendidikan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Pekerjaan Wali</label>

                                        <input type="text"
                                            name="pekerjaan_wali"
                                            value="{{ optional($wali)->pekerjaan }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Penghasilan Wali</label>

                                        <input type="text"
                                            name="penghasilan_wali"
                                            value="{{ optional($wali)->penghasilan }}">
                                    </div>

                                    <div class="form-group full">
                                        <label>Alamat Wali</label>

                                        <textarea name="alamat_wali"
                                                rows="3">{{ optional($wali)->alamat }}</textarea>
                                    </div>

                                </div>
                            </div>

                            <!-- KEMAMPUAN -->
                            <div class="admin-form-section">

                                <div class="section-title">
                                    <i class="fa-solid fa-book-quran"></i>

                                    <div>
                                        <h4>Kemampuan Kepesantrenan</h4>
                                        <p>Kemampuan dasar santri</p>
                                    </div>
                                </div>

                                <div class="admin-form-grid">

                                    <div class="form-group">
                                        <label>Membaca Al-Qur'an</label>

                                        <select name="kemampuan_quran">

                                            <option value="Baik"
                                                {{ $pendaftaran->kemampuan_quran == 'Baik' ? 'selected' : '' }}>
                                                Baik
                                            </option>

                                            <option value="Sedang"
                                                {{ $pendaftaran->kemampuan_quran == 'Sedang' ? 'selected' : '' }}>
                                                Sedang
                                            </option>

                                            <option value="Kurang"
                                                {{ $pendaftaran->kemampuan_quran == 'Kurang' ? 'selected' : '' }}>
                                                Kurang
                                            </option>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Hafalan</label>

                                        <select name="hafalan">

                                            <option value="1-5 Surat"
                                                {{ $pendaftaran->hafalan == '1-5 Surat' ? 'selected' : '' }}>
                                                1-5 Surat
                                            </option>

                                            <option value="5-10 Surat"
                                                {{ $pendaftaran->hafalan == '5-10 Surat' ? 'selected' : '' }}>
                                                5-10 Surat
                                            </option>

                                            <option value="10-15 Surat"
                                                {{ $pendaftaran->hafalan == '10-15 Surat' ? 'selected' : '' }}>
                                                10-15 Surat
                                            </option>

                                            <option value="Diatas 15 Surat"
                                                {{ $pendaftaran->hafalan == 'Diatas 15 Surat' ? 'selected' : '' }}>
                                                Diatas 15 Surat
                                            </option>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Membaca Pegon</label>

                                        <div class="radio-group">

                                            <label>
                                                <input type="radio"
                                                    name="baca_pegon"
                                                    value="1"
                                                    {{ $pendaftaran->baca_pegon == 1 ? 'checked' : '' }}>
                                                Bisa
                                            </label>

                                            <label>
                                                <input type="radio"
                                                    name="baca_pegon"
                                                    value="0"
                                                    {{ $pendaftaran->baca_pegon == 0 ? 'checked' : '' }}>
                                                Belum Bisa
                                            </label>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Menulis Pegon</label>

                                        <div class="radio-group">

                                            <label>
                                                <input type="radio"
                                                    name="tulis_pegon"
                                                    value="1"
                                                    {{ $pendaftaran->tulis_pegon == 1 ? 'checked' : '' }}>
                                                Bisa
                                            </label>

                                            <label>
                                                <input type="radio"
                                                    name="tulis_pegon"
                                                    value="0"
                                                    {{ $pendaftaran->tulis_pegon == 0 ? 'checked' : '' }}>
                                                Belum Bisa
                                            </label>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- EKSTRA -->
                            <div class="admin-form-section">

                                <div class="section-title">
                                    <i class="fa-solid fa-star"></i>

                                    <div>
                                        <h4>Minat & Ekstrakurikuler</h4>
                                        <p>Bakat dan minat santri</p>
                                    </div>
                                </div>

                                <div class="admin-form-grid">

                                    <div class="form-group full">
                                        <label>Bakat & Prestasi</label>

                                        <textarea name="bakat_prestasi"
                                                rows="4">{{ $pendaftaran->bakat_prestasi }}</textarea>
                                    </div>

                                    <div class="form-group full">

                                        <label>Ekstrakurikuler</label>

                                        <div class="checkbox-wrapper">

                                            <label>
                                                <input type="checkbox"
                                                    name="ekstrakurikuler[]"
                                                    value="Hadrah"
                                                    {{ is_array($pendaftaran->ekstrakurikuler) && in_array('Hadrah', $pendaftaran->ekstrakurikuler) ? 'checked' : '' }}>
                                                Hadrah
                                            </label>

                                            <label>
                                                <input type="checkbox"
                                                    name="ekstrakurikuler[]"
                                                    value="Futsal"
                                                    {{ is_array($pendaftaran->ekstrakurikuler) && in_array('Futsal', $pendaftaran->ekstrakurikuler) ? 'checked' : '' }}>
                                                Futsal
                                            </label>

                                            <label>
                                                <input type="checkbox"
                                                    name="ekstrakurikuler[]"
                                                    value="Kaligrafi"
                                                    {{ is_array($pendaftaran->ekstrakurikuler) && in_array('Kaligrafi', $pendaftaran->ekstrakurikuler) ? 'checked' : '' }}>
                                                Kaligrafi
                                            </label>

                                            <label>
                                                <input type="checkbox"
                                                    name="ekstrakurikuler[]"
                                                    value="Tilawah"
                                                    {{ is_array($pendaftaran->ekstrakurikuler) && in_array('Tilawah', $pendaftaran->ekstrakurikuler) ? 'checked' : '' }}>
                                                Tilawah
                                            </label>

                                        </div>
                                    </div>

                                    <div class="form-group">

                                        <label>Size Seragam Pondok</label>

                                        <select name="size_seragam_pondok">

                                            <option value="S"
                                                {{ $pendaftaran->size_seragam_pondok == 'S' ? 'selected' : '' }}>
                                                S
                                            </option>

                                            <option value="M"
                                                {{ $pendaftaran->size_seragam_pondok == 'M' ? 'selected' : '' }}>
                                                M
                                            </option>

                                            <option value="L"
                                                {{ $pendaftaran->size_seragam_pondok == 'L' ? 'selected' : '' }}>
                                                L
                                            </option>

                                            <option value="XL"
                                                {{ $pendaftaran->size_seragam_pondok == 'XL' ? 'selected' : '' }}>
                                                XL
                                            </option>

                                        </select>
                                    </div>

                                    <div class="form-group">

                                        <label>Size Seragam Formal</label>

                                        <select name="size_seragam_formal">

                                            <option value="S"
                                                {{ $pendaftaran->size_seragam_formal == 'S' ? 'selected' : '' }}>
                                                S
                                            </option>

                                            <option value="M"
                                                {{ $pendaftaran->size_seragam_formal == 'M' ? 'selected' : '' }}>
                                                M
                                            </option>

                                            <option value="L"
                                                {{ $pendaftaran->size_seragam_formal == 'L' ? 'selected' : '' }}>
                                                L
                                            </option>

                                            <option value="XL"
                                                {{ $pendaftaran->size_seragam_formal == 'XL' ? 'selected' : '' }}>
                                                XL
                                            </option>

                                        </select>
                                    </div>

                                    <div class="form-group full">

                                        <label>Informasi Masuk Pesantren Dari</label>

                                        <div class="checkbox-group">

                                            <label>
                                                <input type="checkbox"
                                                    name="sumber_info[]"
                                                    value="Media Sosial"
                                                    {{ is_array($pendaftaran->sumber_info) && in_array('Media Sosial', $pendaftaran->sumber_info) ? 'checked' : '' }}>

                                                Media Sosial
                                            </label>

                                            <label>
                                                <input type="checkbox"
                                                    name="sumber_info[]"
                                                    value="Alumni"
                                                    {{ is_array($pendaftaran->sumber_info) && in_array('Alumni', $pendaftaran->sumber_info) ? 'checked' : '' }}>

                                                Alumni
                                            </label>

                                            <label>
                                                <input type="checkbox"
                                                    name="sumber_info[]"
                                                    value="Wali Santri"
                                                    {{ is_array($pendaftaran->sumber_info) && in_array('Wali Santri', $pendaftaran->sumber_info) ? 'checked' : '' }}>

                                                Wali Santri
                                            </label>

                                            <label>
                                                <input type="checkbox"
                                                    name="sumber_info[]"
                                                    value="Lain-lain"
                                                    {{ is_array($pendaftaran->sumber_info) && in_array('Lain-lain', $pendaftaran->sumber_info) ? 'checked' : '' }}>

                                                Lain-lain
                                            </label>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DOKUMEN -->
                            <div class="admin-form-section">

                                <div class="section-title">
                                    <i class="fa-solid fa-file-arrow-up"></i>

                                    <div>
                                        <h4>Dokumen Pendaftaran</h4>
                                        <p>Upload dokumen santri</p>
                                    </div>
                                </div>

                                @php
                                    $documentFields = [
                                        'akta_kelahiran' => 'Akta Kelahiran',
                                        'ktp_ortu' => 'KTP Orang Tua',
                                        'kk' => 'Kartu Keluarga',
                                        'ijazah' => 'Ijazah',
                                        'nisn_file' => 'NISN',
                                        'kip' => 'KIP',
                                        'foto_warna' => 'Foto Warna',
                                        'foto_bw' => 'Foto Hitam Putih',
                                    ];
                                @endphp

                                <div class="admin-form-grid">
                                    @foreach ($documentFields as $fieldName => $documentName)
                                        @php
                                            $document = $pendaftaran->dokumens
                                                ->where('jenis_dokumen', $documentName)
                                                ->first();
                                            $documentUrl = $document ? asset('storage/' . $document->file) : null;
                                            $extension = $document
                                                ? strtolower(pathinfo($document->file, PATHINFO_EXTENSION))
                                                : null;
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp']);
                                        @endphp

                                        <div class="form-group">
                                            <label>{{ $documentName }}</label>

                                            @if ($document)
                                                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; margin-bottom: 10px; background: #f8fafc;">
                                                    @if ($isImage)
                                                        <a href="{{ $documentUrl }}" target="_blank">
                                                            <img src="{{ $documentUrl }}"
                                                                alt="{{ $documentName }}"
                                                                style="width: 100%; height: 140px; object-fit: cover; border-radius: 10px; margin-bottom: 10px;">
                                                        </a>
                                                    @else
                                                        <div style="height: 120px; border-radius: 10px; background: #fff; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                                            <i class="fa-regular fa-file-pdf" style="font-size: 2.5rem; color: #ef4444;"></i>
                                                        </div>
                                                    @endif

                                                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                                        <a href="{{ $documentUrl }}"
                                                            target="_blank"
                                                            class="btn-save"
                                                            style="padding: 8px 12px; text-decoration: none;">
                                                            <i class="fa-regular fa-eye"></i>
                                                            Lihat
                                                        </a>

                                                        <a href="{{ $documentUrl }}"
                                                            download
                                                            class="btn-cancle"
                                                            style="padding: 8px 12px; text-decoration: none;">
                                                            <i class="fa-solid fa-download"></i>
                                                            Download
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <div style="border: 1px dashed #cbd5e1; border-radius: 12px; padding: 16px; margin-bottom: 10px; color: #64748b; background: #f8fafc;">
                                                    Belum ada dokumen
                                                </div>
                                            @endif

                                            <input type="file" name="{{ $fieldName }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <!-- FOOTER -->
                        <div class="modal-footer">

                            <button type="button"
                                    class="btn-cancel"
                                    data-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit"
                                    class="btn-save">

                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan Perubahan
                            </button>

                        </div>

                    </form>
                </div>
            </div>
        @endforeach
    @endif
    <!-- ===================================================== -->
    <!-- MODAL PEMBAYARAN -->
    <!-- ===================================================== -->
    @isset($pendaftarans)
        @foreach($pendaftarans as $pendaftaran)
    @php
        $tagihan = $pendaftaran->tagihanSantri;

        $totalLunas = $tagihan?->details
            ?->where('status_pembayaran', 'lunas')
            ->sum('nominal_akhir') ?? 0;

        $totalBelumBayar = $tagihan?->details
            ?->where('status_pembayaran', '!=', 'lunas')
            ->sum('nominal_akhir') ?? 0;
    @endphp

    <div class="modal fade admin-modal"
        id="modalBayar{{ $pendaftaran->id }}"
        tabindex="-1">

        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <div class="modal-title-wrap">
                        <h3>Detail Pembayaran Santri</h3>
                        <span>Riwayat pembayaran dan tagihan santri</span>
                    </div>

                    <button type="button"
                        class="close-modal"
                        data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <!-- PROFILE -->
                    <div class="payment-student-info">
                        <div class="student-left">
                            <img src="{{ asset('assets/galeri1.jpg') }}" alt="Santri">

                            <div>
                                <h4>{{ $pendaftaran->nama_lengkap }}</h4>

                                <span>
                                    NISN :
                                    {{ $pendaftaran->pendidikan?->nisn ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="student-right">
                            <span class="badge-status">
                                {{ $pendaftaran->pendidikan?->jenjang_pendidikan ?? 'Santri' }}
                            </span>
                        </div>
                    </div>

                    <!-- LIST TAGIHAN -->
                    <div class="bill-list">

                        @if ($tagihan && $tagihan->details->count())

                            @foreach ($tagihan->details->groupBy('kategori') as $kategori => $details)

                                <!-- CATEGORY -->
                                <div class="bill-category">
                                    <h5>{{ $kategori }}</h5>
                                </div>

                                @foreach ($details as $detail)

                                    @php
                                        $isLunas = $detail->status_pembayaran === 'lunas';
                                    @endphp

                                    <!-- ITEM -->
                                    <div class="bill-item disabled">

                                        <div class="bill-left">
                                            <div>
                                                <h5>
                                                    {{ $detail->nama_pembayaran }}
                                                </h5>

                                                <p>
                                                    {{ $detail->kategori }}

                                                    @if (!empty($detail->nama_promo))
                                                        - Promo:
                                                        {{ $detail->nama_promo }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="bill-right">

                                            @if (($detail->potongan_promo ?? 0) > 0)
                                                <small>
                                                    <s>
                                                        Rp {{ number_format($detail->nominal_awal, 0, ',', '.') }}
                                                    </s>
                                                </small>
                                            @endif

                                            <strong>
                                                Rp {{ number_format($detail->nominal_akhir, 0, ',', '.') }}
                                            </strong>

                                            @if ($isLunas)
                                                <span class="status lunas">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                    Lunas
                                                </span>
                                            @else
                                                <span class="status pending">
                                                    <i class="fa-regular fa-clock"></i>
                                                    Belum Bayar
                                                </span>
                                            @endif

                                        </div>
                                    </div>

                                @endforeach

                            @endforeach

                        @else

                            <div class="text-center py-4">
                                <p class="mb-0">
                                    Belum ada data tagihan.
                                </p>
                            </div>

                        @endif

                    </div>

                    <!-- FOOTER SUMMARY -->
                    <div class="payment-summary">

                        <div class="summary-card">
                            <span>Total Lunas</span>

                            <h4>
                                Rp {{ number_format($totalLunas, 0, ',', '.') }}
                            </h4>
                        </div>

                        <div class="summary-card warning">
                            <span>Total Belum Bayar</span>

                            <h4>
                                Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    @endforeach
    @endisset

    <!-- ===================================================== -->
    <!-- MODAL PRINT FORMULIR PENDAFTARAN -->
    <!-- ===================================================== -->
    @isset($pendaftarans)
        @foreach($pendaftarans as $pendaftaran)
            <div class="modal fade admin-modal" id="modalPrintPendaftaran{{$pendaftaran->id}}" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <!-- HEADER -->
                        <div class="modal-header">
                            <div class="modal-title-wrap">
                                <h3>
                                    Preview Formulir Pendaftaran
                                </h3>
                                <span>
                                    Cetak formulir santri baru
                                </span>
                            </div>
                            <button type="button" class="close-modal" data-dismiss="modal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <!-- BODY -->
                        <div class="modal-body">
                            <iframe
                                src="{{ route('admin.pendaftaran.preview', $pendaftaran->id) }}"
                                width="100%"
                                height="700"
                                style="border:none;">
                            </iframe>
                        </div>
                        <!-- FOOTER -->
                        <div class="modal-footer">
                            <button class="btn-cancel" data-dismiss="modal">
                                Batal
                            </button>
                            <a href="{{ route('admin.pendaftaran.print', $pendaftaran->id)}}" class="btn-save">
                                <i class="fa-solid fa-print"></i>
                                Print PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endisset

    @if(isset($pendaftarans) && ($canManageSelectedPeriod ?? true))
    <!-- HAPUS MODAL PENDAFTARAN -->
        @foreach($pendaftarans as $pendaftaran)
            <div class="modal fade delete-modal" id="modalHapusPendaftaran{{$pendaftaran->id}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content" action="{{ route('pendaftaran.destroy', $pendaftaran->id) }}" method="POST">
                        @csrf
                        @method('DELETE')>
                        <!-- ICON -->
                        <div class="delete-icon">
                            <i class="fa-regular fa-trash-can"></i>
                        </div>
                        <!-- CONTENT -->
                        <div class="delete-content">
                            <span class="delete-label">
                                Konfirmasi Penghapusan
                            </span>
                            <h3>
                                Yakin ingin menghapus data ini?
                            </h3>
                            <p>
                                Data yang sudah dihapus tidak dapat dikembalikan lagi.
                                Pastikan tindakan ini sudah benar.
                            </p>
                        </div>
                        <!-- ACTION -->
                        <div class="delete-action">
                            <button type="button" class="btn-cancel" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="button" class="btn-delete-confirm" data-dismiss="modal">
                                <i class="fa-solid fa-trash"></i>
                                Hapus Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

    <!-- MODAL TAMBAH GELOMBANG -->
    <div class="modal fade admin-modal"
        id="modalTambahGelombang"
        tabindex="-1"
        aria-labelledby="modalTambahGelombangLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- HEADER -->
                <div class="modal-header">
                    <div class="header-content">
                        <div>
                            <h3 class="modal-title-wrap">
                                Tambah Gelombang Pendaftaran
                            </h3>
                            <span>
                                Tambahkan informasi gelombang pendaftaran santri baru
                            </span>
                        </div>
                    </div>
                    <button type="button"
                            class="close-modal"
                            data-dismiss="modal"
                            aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <!-- BODY -->
                <div class="modal-body">
                    <form action="{{ route('gelombang.store') }}"
                        method="POST"
                        id="formTambahGelombang">
                        @csrf
                        <div class="form-section">
                            <div class="section-title">
                                <h4>
                                    Informasi Gelombang
                                </h4>
                            </div>
                            <div class="form-grid">
                                <!-- NAMA -->
                                <div class="form-group">
                                    <label>
                                        Nama Gelombang
                                    </label>
                                    <input type="text"
                                        name="nama_gelombang"
                                        value="{{ old('nama_gelombang') }}"
                                        placeholder="Contoh : Gelombang 1">
                                    @error('nama_gelombang')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                                <!-- STATUS -->
                                <div class="form-group">
                                    <label>
                                        Publish Gelombang
                                    </label>
                                    <select name="is_publish">
                                        <option value="1" selected>
                                            Dipublish
                                        </option>
                                        <option value="0">
                                            Draft
                                        </option>
                                    </select>
                                    <small class="text-muted">
                                        Status aktif tetap otomatis berdasarkan tanggal
                                    </small>
                                </div>
                                <!-- TANGGAL MULAI -->
                                <div class="form-group">
                                    <label>
                                        Tanggal Mulai
                                    </label>
                                    <input type="date"
                                        name="tanggal_mulai"
                                        value="{{ old('tanggal_mulai') }}">
                                    @error('tanggal_mulai')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                                <!-- TANGGAL SELESAI -->
                                <div class="form-group">
                                    <label>
                                        Tanggal Selesai
                                    </label>
                                    <input type="date"
                                        name="tanggal_selesai"
                                        value="{{ old('tanggal_selesai') }}">
                                    @error('tanggal_selesai')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                                <!-- URUTAN -->
                                <div class="form-group">
                                    <label>
                                        Urutan Tampil
                                    </label>
                                    <input type="number"
                                        name="urutan"
                                        value="{{ old('urutan', 1) }}"
                                        placeholder="1">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button"
                            class="btn-cancle"
                            data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit"
                            form="formTambahGelombang"
                            class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Gelombang
                    </button>
                </div>
            </div>
        </div>
    </div>

    @isset($gelombangs)
        <!-- MODAL EDIT GELOMBANG -->
        @foreach ($gelombangs as $item)
            <div class="modal fade admin-modal"
                id="modalEditGelombang{{ $item->id }}"
                tabindex="-1"
                aria-labelledby="modalTambahGelombangLabel"
                aria-hidden="true">

                <div class="modal-dialog modal-xl modal-dialog-centered">

                    <div class="modal-content">

                        <!-- HEADER -->
                        <div class="modal-header">

                            <div class="header-content">

                                <div>

                                    <h3 class="modal-title-wrap">
                                        Edit Gelombang Pendaftaran
                                    </h3>

                                    <span>
                                        Perbarui informasi gelombang pendaftaran santri baru
                                    </span>

                                </div>

                            </div>

                            <button type="button"
                                    class="close-modal"
                                    data-dismiss="modal"
                                    aria-label="Close">

                                <i class="fa-solid fa-xmark"></i>

                            </button>

                        </div>

                        <!-- BODY -->
                        <div class="modal-body">

                            <form action="{{ route('gelombang.update', $item->id) }}"
                                method="POST"
                                id="formEditGelombang{{ $item->id }}">

                                @csrf
                                <div class="form-section">

                                    <div class="section-title">

                                        <h4>
                                            Informasi Gelombang
                                        </h4>

                                    </div>

                                    <div class="form-grid">

                                        <!-- NAMA -->
                                        <div class="form-group">

                                            <label>
                                                Nama Gelombang
                                            </label>

                                            <input type="text"
                                                name="nama_gelombang"
                                                value="{{ old('nama_gelombang', $item->nama_gelombang) }}"
                                                placeholder="Contoh : Gelombang 1">

                                        </div>

                                        <!-- STATUS -->
                                        <div class="form-group">

                                            <label>
                                                Publish Gelombang
                                            </label>

                                            <select name="is_publish">

                                                <option value="1" @selected(old('is_publish', $item->is_publish) == true)>
                                                    Dipublish
                                                </option>

                                                <option value="0" @selected(old('is_publish', $item->is_publish) == false)>
                                                    Draft
                                                </option>

                                            </select>

                                            <small class="text-muted">

                                                Status saat ini: 
                                                @if ($item->status == 'aktif')
                                                    Aktif
                                                @elseif ($item->status == 'akan_datang')
                                                    Akan Datang
                                                @else
                                                    Ditutup
                                                @endif

                                            </small>

                                        </div>

                                        <!-- TANGGAL MULAI -->
                                        <div class="form-group">

                                            <label>
                                                Tanggal Mulai
                                            </label>

                                            <input type="date"
                                                name="tanggal_mulai"
                                                value="{{ old('tanggal_mulai', $item->tanggal_mulai?->format('Y-m-d')) }}">

                                        </div>

                                        <!-- TANGGAL SELESAI -->
                                        <div class="form-group">

                                            <label>
                                                Tanggal Selesai
                                            </label>

                                            <input type="date"
                                                name="tanggal_selesai"
                                                value="{{ old('tanggal_selesai', $item->tanggal_selesai?->format('Y-m-d')) }}">

                                        </div>

                                        <!-- URUTAN -->
                                        <div class="form-group">

                                            <label>
                                                Urutan Tampil
                                            </label>

                                            <input type="number"
                                                name="urutan"
                                                value="{{ old('urutan', $item->urutan) }}"
                                                placeholder="1">

                                        </div>

                                    </div>

                                </div>

                            </form>

                        </div>

                        <!-- FOOTER -->
                        <div class="modal-footer">

                            <button type="button"
                                    class="btn-cancle"
                                    data-dismiss="modal">

                                Batal

                            </button>

                            <button type="submit"
                                    form="formEditGelombang{{ $item->id }}"
                                    class="btn-save">

                                <i class="fa-solid fa-floppy-disk"></i>

                                Simpan Gelombang

                            </button>

                        </div>

                    </div>

                </div>

            </div>
        @endforeach
    @endisset

    @isset($gelombangs)
    <!-- MODAL VIEW GELOMBANG -->
    @foreach ($gelombangs as $item)
        <div class="modal fade admin-modal"
            id="modalViewGelombang{{ $item->id }}"
            tabindex="-1"
            aria-labelledby="modalTambahGelombangLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-xl modal-dialog-centered">

                <div class="modal-content gelombang-view">

                    <!-- HEADER -->
                    <div class="modal-header">

                        <div class="header-content">

                            <div>

                                <h3 class="modal-title-wrap">
                                    View Gelombang Pendaftaran
                                </h3>

                                <span>
                                    Lihat informasi gelombang pendaftaran santri baru
                                </span>

                            </div>

                        </div>

                        <button type="button"
                                class="close-modal"
                                data-dismiss="modal"
                                aria-label="Close">

                            <i class="fa-solid fa-xmark"></i>

                        </button>

                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <div class="jadwal-card

                            @if (! $item->is_publish)
                                warning
                            @elseif ($item->status == 'aktif')
                                active
                            @elseif ($item->status == 'akan_datang')
                                warning
                            @else
                                danger
                            @endif

                        ">

                            {{-- BADGE --}}
                            <span class="badge">

                                @if (! $item->is_publish)

                                    DRAFT

                                @elseif ($item->status == 'aktif')

                                    AKTIF

                                @elseif ($item->status == 'akan_datang')

                                    AKAN DATANG

                                @else

                                    DITUTUP

                                @endif

                            </span>

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

                            {{-- KETERANGAN --}}
                            <p>
                                Urutan tampil: {{ $item->urutan }}

                            </p>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">

                        <button type="button"
                                class="btn-cancle"
                                data-dismiss="modal">

                            Batal

                        </button>

                        <button type="button"
                                class="btn-save"
                                data-dismiss="modal"
                                data-toggle="modal"
                                data-target="#modalEditGelombang{{ $item->id }}">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Edit Gelombang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @endisset

    @isset($gelombangs)
    <!-- HAPUS MODAL GELOMBANG -->
    @foreach ($gelombangs as $item)
        <div class="modal fade delete-modal"
            id="modalHapusGelombang{{ $item->id }}"
            tabindex="-1"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <!-- ICON -->
                    <div class="delete-icon">

                        <i class="fa-regular fa-trash-can"></i>

                    </div>

                    <!-- CONTENT -->
                    <div class="delete-content">

                    <span class="delete-label">
                        Konfirmasi Penghapusan
                    </span>

                    <h3>
                        Yakin ingin menghapus data ini?
                    </h3>

                    <p>
                        Data yang sudah dihapus tidak dapat dikembalikan lagi.
                        Pastikan tindakan ini sudah benar.
                    </p>

                </div>

                    <!-- ACTION -->
                    <div class="delete-action">

                        <button type="button"
                                class="btn-cancel"
                                data-dismiss="modal">

                            Batal

                        </button>

                        <form action="{{ route('gelombang.destroy', $item->id) }}"
                            method="POST">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn-delete-confirm">

                                <i class="fa-solid fa-trash"></i>

                                Hapus Data

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>
    @endforeach
    @endisset

    <!-- MODAL TAMBAH BIAYA -->
    <div class="modal fade admin-modal" id="modalTambahBiaya" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- FORM -->
                <form
                    action="{{ route('pembayaran.store') }}"
                    method="POST"
                    id="formTambahPembayaran">
                    @csrf
                    <!-- HEADER -->
                    <div class="modal-header">
                        <div class="modal-title-wrap">
                            <h3>
                                Tambah Informasi Pembayaran
                            </h3>
                            <span>
                                Tambahkan rincian biaya pendaftaran santri
                            </span>
                        </div>
                        <button
                            type="button"
                            class="close-modal"
                            data-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <!-- BODY -->
                    <div class="modal-body">
                        <div class="form-grid">
                            <!-- JENJANG -->
                            <div class="form-group">
                                <label>
                                    Jenjang Pendidikan
                                </label>
                                <select
                                    name="jenjang"
                                    required>
                                    <option value="">
                                        Pilih Jenjang
                                    </option>
                                    <option value="SMP">
                                        SMP
                                    </option>

                                    <option value="SMK">
                                        SMK
                                    </option>
                                </select>
                                @error('jenjang')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <!-- KATEGORI -->
                            <div class="form-group">
                                <label>
                                    Kategori Pembayaran
                                </label>
                                <select
                                    name="kategori"
                                    required>
                                    <option value="">
                                        Pilih Kategori
                                    </option>
                                    <option value="Biaya Tahunan">
                                        Biaya Tahunan
                                    </option>
                                    <option value="Biaya Bulanan">
                                        Biaya Bulanan
                                    </option>
                                </select>
                                @error('kategori')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <!-- NAMA PEMBAYARAN -->
                            <div class="form-group full">
                                <label>
                                    Nama Pembayaran
                                </label>
                                <input
                                    type="text"
                                    name="nama_pembayaran"
                                    placeholder="Contoh : Registrasi Pondok"
                                    value="{{ old('nama_pembayaran') }}"
                                    required>
                                @error('nama_pembayaran')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <!-- NOMINAL -->
                            <div class="form-group">
                                <label>
                                    Nominal Pembayaran
                                </label>
                                <input
                                    type="text"
                                    name="nominal"
                                    class="editNominal"
                                    placeholder="Contoh : Rp 150.000"
                                    value="{{ old('nominal') }}"
                                    required>
                                @error('nominal')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn-cancel"
                            data-dismiss="modal">
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @isset($pembayarans)
    <!-- MODAL EDIT BIAYA -->
    @foreach ($pembayarans as $item)
    <div class="modal fade admin-modal"
        id="modalEditBiaya{{ $item->id }}"
        tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- FORM -->
                <form
                    action="{{ route('pembayaran.update', $item->id) }}"
                    method="POST">
                    @csrf
                    <!-- HEADER -->
                    <div class="modal-header">
                        <div class="modal-title-wrap">
                            <h3>
                                Edit Informasi Pembayaran
                            </h3>
                            <span>
                                Edit rincian biaya pendaftaran santri
                            </span>
                        </div>
                        <button
                            type="button"
                            class="close-modal"
                            data-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <!-- BODY -->
                    <div class="modal-body">
                        <div class="form-grid">
                            <!-- JENJANG -->
                            <div class="form-group">
                                <label>
                                    Jenjang Pendidikan
                                </label>
                                <select
                                    name="jenjang"
                                    required>
                                    <option value="SMP"
                                        {{ $item->jenjang == 'SMP'
                                            ? 'selected'
                                            : '' }}>
                                        SMP
                                    </option>
                                    <option value="SMK"
                                        {{ $item->jenjang == 'SMK'
                                            ? 'selected'
                                            : '' }}>
                                        SMK
                                    </option>
                                </select>
                            </div>
                            <!-- KATEGORI -->
                            <div class="form-group">
                                <label>
                                    Kategori Pembayaran
                                </label>
                                <select
                                    name="kategori"
                                    required>
                                    <option value="Biaya Tahunan"
                                        {{ $item->kategori == 'Biaya Tahunan'
                                            ? 'selected'
                                            : '' }}>
                                        Biaya Tahunan
                                    </option>
                                    <option value="Biaya Bulanan"
                                        {{ $item->kategori == 'Biaya Bulanan'
                                            ? 'selected'
                                            : '' }}>
                                        Biaya Bulanan
                                    </option>
                                </select>
                            </div>
                            <!-- NAMA PEMBAYARAN -->
                            <div class="form-group full">
                                <label>
                                    Nama Pembayaran
                                </label>
                                <input
                                    type="text"
                                    name="nama_pembayaran"
                                    value="{{ $item->nama_pembayaran }}"
                                    required>
                            </div>
                            <!-- NOMINAL -->
                            <div class="form-group">
                                <label>
                                    Nominal Pembayaran
                                </label>
                                <input
                                    type="text"
                                    name="nominal"
                                    class="editNominal"
                                    value="Rp {{ number_format($item->nominal, 0, ',', '.') }}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn-cancel"
                            data-dismiss="modal">
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    @endisset

    @isset($pembayarans)
    <!-- MODAL VIEW BIAYA -->
    @foreach ($pembayarans as $item)
        <div
            class="modal fade admin-modal"
            id="modalViewBiaya{{ $item->id }}"
            tabindex="-1">

            <div class="modal-dialog modal-xl modal-dialog-centered">

                <div class="modal-content">

                    <!-- HEADER -->
                    <div class="modal-header">

                        <div class="modal-title-wrap">

                            <h3>
                                View Informasi Pembayaran
                            </h3>

                            <span>
                                Lihat rincian biaya pendaftaran santri
                            </span>

                        </div>

                        <button
                            type="button"
                            class="close-modal"
                            data-dismiss="modal">

                            <i class="fa-solid fa-xmark"></i>

                        </button>

                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <div class="biaya-grid">

                            <div class="biaya-card">

                                <div class="card-header">

                                    <h3>

                                        @if ($item->jenjang == 'SMP')

                                            <i class="fa-solid fa-school"></i>

                                            SMP Ma'arif Darus Sholihin

                                        @else

                                            <i class="fa-solid fa-laptop-code"></i>

                                            SMK Ma'arif Darus Sholihin

                                        @endif

                                    </h3>

                                    <span class="badge">

                                        {{ $item->kategori }}

                                    </span>

                                </div>

                                <div class="table-wrapper">

                                    <table>

                                        <tbody>

                                            <tr class="category">

                                                <td colspan="2">

                                                    {{ $item->kategori }}

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>

                                                    {{ $item->nama_pembayaran }}

                                                </td>

                                                <td>

                                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}

                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">

                        <button
                            class="btn-cancel"
                            data-dismiss="modal">

                            Batal

                        </button>

                        <button
                            class="btn-save"
                            data-dismiss="modal"
                            data-toggle="modal"
                            data-target="#modalEditBiaya{{ $item->id }}">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Edit Pembayaran

                        </button>

                    </div>

                </div>

            </div>

        </div>
    @endforeach
    @endisset

    <!-- HAPUS MODAL BIAYA -->
    <div class="modal fade delete-modal" id="modalHapusBiaya" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- ICON -->
                <div class="delete-icon">
                    <i class="fa-regular fa-trash-can"></i>
                </div>
                <!-- CONTENT -->
                <div class="delete-content">
                    <span class="delete-label">
                        Konfirmasi Penghapusan
                    </span>
                    <h3>
                        Yakin ingin menghapus data ini?
                    </h3>
                    <p>
                        Data yang sudah dihapus tidak dapat dikembalikan lagi.
                        Pastikan tindakan ini sudah benar.
                    </p>
                </div>
                <!-- ACTION -->
                <div class="delete-action">
                    <button type="button" class="btn-cancel" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn-delete-confirm" data-dismiss="modal">
                        <i class="fa-solid fa-trash"></i>
                        Hapus Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const editNominalInputs =
            document.querySelectorAll('.editNominal');
        editNominalInputs.forEach(input => {
            input.addEventListener('keyup', function() {
                let angka =
                    this.value.replace(/[^,\d]/g, '').toString();
                let split =
                    angka.split(',');
                let sisa =
                    split[0].length % 3;
                let rupiah =
                    split[0].substr(0, sisa);
                let ribuan =
                    split[0]
                    .substr(sisa)
                    .match(/\d{3}/gi);
                if (ribuan) {
                    let separator =
                        sisa ? '.' : '';
                    rupiah +=
                        separator + ribuan.join('.');
                }
                rupiah =
                    split[1] != undefined
                    ? rupiah + ',' + split[1]
                    : rupiah;
                this.value =
                    'Rp ' + rupiah;
            });
        });
    </script>

    {{-- TAMBAH HASIL TES --}}
    <div class="modal fade admin-modal"
        id="modalTambahNilai"
        tabindex="-1"
        aria-labelledby="modalTambahNilaiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <div class="header-content">
                        <div>
                            <h3 class="modal-title-wrap">
                                Tambah Nilai Tes Santri
                            </h3>
                            <span>
                                Input hasil tes kepesantrenan, Al-Qur'an, dan wawancara santri
                            </span>
                        </div>
                    </div>

                    <button type="button"
                        class="close-modal"
                        data-dismiss="modal"
                        aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <form action="{{ route('hasil-tes.store') }}"
                        method="POST"
                        id="formTambahNilai">

                        @csrf

                        <div class="form-section">

                            <div class="section-title">
                                <h4>
                                    Data Santri
                                </h4>
                            </div>

                            <div class="form-grid">

                                <div class="form-group full">
                                    <label>
                                        Pilih Santri
                                    </label>

                                    <select name="pendaftaran_id" required>
                                        <option value="">
                                            Pilih Santri
                                        </option>
                                        @isset($pendaftarans)
                                            @foreach ($pendaftarans as $pendaftaran)
                                                <option value="{{ $pendaftaran->id }}">
                                                    {{ $pendaftaran->nama_lengkap }}
                                                    -
                                                    {{ $pendaftaran->pendidikan->nisn ?? 'NISN belum ada' }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>

                            </div>

                        </div>

                        <!-- TES KEPESANTRENAN -->
                        <div class="form-section">

                            <div class="section-title">
                                <h4>
                                    Tes Kepesantrenan
                                </h4>
                            </div>

                            <div class="form-grid">

                                <div class="form-group">
                                    <label>
                                        Baca Tulis Pegon
                                    </label>
                                    <input type="number"
                                        name="baca_tulis_pegon"
                                        min="0"
                                        max="100"
                                        placeholder="0 - 100">
                                </div>

                                <div class="form-group">
                                    <label>
                                        Doa Harian
                                    </label>
                                    <input type="number"
                                        name="doa_harian"
                                        min="0"
                                        max="100"
                                        placeholder="0 - 100">
                                </div>

                                <div class="form-group">
                                    <label>
                                        Ubudiyah Harian
                                    </label>
                                    <input type="number"
                                        name="ubudiyyah"
                                        min="0"
                                        max="100"
                                        placeholder="0 - 100">
                                </div>

                            </div>

                        </div>

                        <!-- TES AL-QUR'AN -->
                        <div class="form-section">

                            <div class="section-title">
                                <h4>
                                    Tes Al-Qur'an
                                </h4>
                            </div>

                            <div class="form-grid">

                                <div class="form-group">
                                    <label>
                                        Membaca Al-Qur'an
                                    </label>
                                    <input type="number"
                                        name="membaca_al_quran"
                                        min="0"
                                        max="100"
                                        placeholder="0 - 100">
                                </div>

                                <div class="form-group">
                                    <label>
                                        Hafalan Surat Pendek
                                    </label>
                                    <input type="number"
                                        name="hafalan_surat_pendek"
                                        min="0"
                                        max="100"
                                        placeholder="0 - 100">
                                </div>

                            </div>

                        </div>

                        <!-- WAWANCARA -->
                        <div class="form-section">

                            <div class="section-title">
                                <h4>
                                    Tes Wawancara
                                </h4>
                            </div>

                            <div class="form-grid">

                                <div class="form-group full">
                                    <label>
                                        Hasil Wawancara
                                    </label>

                                    <input type="text"
                                        name="wawancara"
                                        placeholder="Contoh : Sangat Baik">
                                </div>

                            </div>

                        </div>

                    </form>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button"
                        class="btn-cancle"
                        data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                        form="formTambahNilai"
                        class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Nilai Tes
                    </button>
                </div>

            </div>
        </div>
    </div>


    {{-- MODAL EDIT DAN HAPUS HASIL TES --}}
@isset($hasilTesModal)
    @foreach ($hasilTesModal as $hasil)
        <div class="modal fade admin-modal"
            id="modalEditHasil{{ $hasil->id }}"
            tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <!-- HEADER -->
                    <div class="modal-header">
                        <div class="header-content">
                            <div>
                                <h3 class="modal-title-wrap">
                                    Edit Nilai Tes Santri
                                </h3>
                                <span>
                                    Perbarui hasil tes kepesantrenan, Al-Qur'an, dan wawancara
                                </span>
                            </div>
                        </div>

                        <button type="button"
                            class="close-modal"
                            data-dismiss="modal"
                            aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <form action="{{ route('hasil-tes.update', $hasil->id) }}"
                            method="POST"
                            id="formEditHasil{{ $hasil->id }}">
                            @csrf
                            <input type="hidden"
                                name="pendaftaran_id"
                                value="{{ $hasil->pendaftaran_id }}">

                            <!-- DATA SANTRI -->
                            <div class="form-section">

                                <div class="section-title">
                                    <h4>
                                        Data Santri
                                    </h4>
                                </div>

                                <div class="form-grid">

                                    <div class="form-group full">
                                        <label>
                                            Nama Santri
                                        </label>

                                        <input type="text"
                                            value="{{ $hasil->pendaftaran->nama_lengkap ?? '-' }}"
                                            disabled>
                                    </div>

                                </div>

                            </div>

                            <!-- TES KEPESANTRENAN -->
                            <div class="form-section">

                                <div class="section-title">
                                    <h4>
                                        Tes Kepesantrenan
                                    </h4>
                                </div>

                                <div class="form-grid">

                                    <div class="form-group">
                                        <label>
                                            Baca Tulis Pegon
                                        </label>

                                        <input type="number"
                                            name="baca_tulis_pegon"
                                            min="0"
                                            max="100"
                                            value="{{ old('baca_tulis_pegon', $hasil->baca_tulis_pegon) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            Doa Harian
                                        </label>

                                        <input type="number"
                                            name="doa_harian"
                                            min="0"
                                            max="100"
                                            value="{{ old('doa_harian', $hasil->doa_harian) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            Ubudiyah Harian
                                        </label>

                                        <input type="number"
                                            name="ubudiyyah"
                                            min="0"
                                            max="100"
                                            value="{{ old('ubudiyyah', $hasil->ubudiyyah) }}">
                                    </div>

                                </div>

                            </div>

                            <!-- TES AL-QUR'AN -->
                            <div class="form-section">

                                <div class="section-title">
                                    <h4>
                                        Tes Al-Qur'an
                                    </h4>
                                </div>

                                <div class="form-grid">

                                    <div class="form-group">
                                        <label>
                                            Membaca Al-Qur'an
                                        </label>

                                        <input type="number"
                                            name="membaca_al_quran"
                                            min="0"
                                            max="100"
                                            value="{{ old('membaca_al_quran', $hasil->membaca_al_quran) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            Hafalan Surat Pendek
                                        </label>

                                        <input type="number"
                                            name="hafalan_surat_pendek"
                                            min="0"
                                            max="100"
                                            value="{{ old('hafalan_surat_pendek', $hasil->hafalan_surat_pendek) }}">
                                    </div>

                                </div>

                            </div>

                            <!-- WAWANCARA -->
                            <div class="form-section">

                                <div class="section-title">
                                    <h4>
                                        Tes Wawancara
                                    </h4>
                                </div>

                                <div class="form-grid">

                                    <div class="form-group full">
                                        <label>
                                            Hasil Wawancara
                                        </label>

                                        <input type="text"
                                            name="wawancara"
                                            value="{{ old('wawancara', $hasil->wawancara) }}"
                                            placeholder="Contoh : Sangat Baik">
                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">

                        <button type="button"
                            class="btn-cancle"
                            data-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit"
                            form="formEditHasil{{ $hasil->id }}"
                            class="btn-save">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Update Nilai
                        </button>

                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modalHapusHasil{{ $hasil->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- ICON -->
                    <div class="delete-icon">
                        <i class="fa-regular fa-trash-can"></i>
                    </div>
                    <!-- CONTENT -->
                    <div class="delete-content">
                    <span class="delete-label">
                        Konfirmasi Penghapusan
                    </span>
                    <h3>
                        Yakin ingin menghapus data ini?
                    </h3>
                    <p>
                        Data yang sudah dihapus tidak dapat dikembalikan lagi.
                        Pastikan tindakan ini sudah benar.
                    </p>
                </div>
                    <!-- ACTION -->
                    <div class="delete-action">
                        <button type="button"
                                class="btn-cancel"
                                data-dismiss="modal">
                            Batal
                        </button>
                        <form action="{{ route('hasil-tes.destroy', $hasil->id) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn-delete-confirm">
                                <i class="fa-solid fa-trash"></i>
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endisset

    {{-- Modal Virtual Tour --}}
    @if (request()->routeIs('admin-virtual-tour'))
        <div class="modal fade admin-modal virtual-tour-modal" id="modalTambahScene" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form method="POST" action="{{ route('virtual-tour.scene.store') }}" enctype="multipart/form-data"
                    id="formTambahSceneVirtualTour" class="modal-content">
                    @csrf

                    <div class="modal-header">
                        <div class="modal-title-wrap">
                            <span>Virtual Tour CMS</span>
                            <h3>Tambah Lokasi Virtual Tour</h3>
                        </div>
                        <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="virtual-tour-form">
                            @include('pages.admin.partials.form-virtual-tour-scene', [
                                'scene' => null,
                            ])
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Scene
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($activeScene ?? false)
            <div class="modal fade admin-modal virtual-tour-modal" id="modalEditScene" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <form method="POST" action="{{ route('virtual-tour.scene.update', $activeScene->id) }}"
                        enctype="multipart/form-data" id="formEditSceneVirtualTour" class="modal-content">
                        @csrf

                        <div class="modal-header">
                            <div class="modal-title-wrap">
                                <span>Virtual Tour CMS</span>
                                <h3>Edit Scene</h3>
                            </div>
                            <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="virtual-tour-form">
                                @include('pages.admin.partials.form-virtual-tour-scene', [
                                    'scene' => $activeScene,
                                ])
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn-save">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Update Scene
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade admin-modal virtual-tour-modal" id="modalTambahHotspot" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <form method="POST" action="{{ route('virtual-tour.hotspot.store', $activeScene->id) }}"
                        id="formTambahHotspotVirtualTour" class="modal-content">
                        @csrf

                        <div class="modal-header">
                            <div class="modal-title-wrap">
                                <span>{{ $activeScene->nama_lokasi }}</span>
                                <h3>Tambah Hotspot</h3>
                            </div>
                            <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="virtual-tour-form">
                                @include('pages.admin.partials.form-virtual-tour-hotspot', [
                                    'hotspot' => null,
                                    'allScenes' => $allScenes,
                                    'activeScene' => $activeScene,
                                ])
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn-save">
                                <i class="fa-solid fa-location-dot"></i>
                                Simpan Hotspot
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @foreach ($activeScene->hotspots as $hotspot)
                <div class="modal fade admin-modal virtual-tour-modal" id="modalEditHotspot{{ $hotspot->id }}"
                    tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-title-wrap">
                                    <span>{{ $activeScene->nama_lokasi }}</span>
                                    <h3>Edit Hotspot</h3>
                                </div>
                                <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="{{ route('virtual-tour.hotspot.update', $hotspot->id) }}"
                                    id="formEditHotspotVirtualTour{{ $hotspot->id }}" class="virtual-tour-form">
                                    @csrf

                                    @include('pages.admin.partials.form-virtual-tour-hotspot', [
                                        'hotspot' => $hotspot,
                                        'allScenes' => $allScenes,
                                        'activeScene' => $activeScene,
                                    ])
                                </form>
                            </div>

                            <div class="modal-footer">
                                <form method="POST" action="{{ route('virtual-tour.hotspot.destroy', $hotspot->id) }}"
                                    class="virtual-tour-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete"
                                        onclick="return confirm('Hapus hotspot ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                        Hapus Hotspot
                                    </button>
                                </form>
                                <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                                <button type="submit" form="formEditHotspotVirtualTour{{ $hotspot->id }}" class="btn-save">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Update Hotspot
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @endif
</body>

</html>
