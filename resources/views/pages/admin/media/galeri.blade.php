@extends('layout.app')
@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')
        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Manajemen Galeri'])
            <section class="gallery-admin">
                {{-- STAT --}}
                <div class="gallery-stat-grid">
                    <div class="gallery-stat-card">
                        <div class="icon blue">
                            <i class="fa-solid fa-images"></i>
                        </div>
                        <div class="text">
                            <h3>{{ $jumlahGaleri }}</h3>
                            <span>Total Galeri</span>
                        </div>
                    </div>
                    <div class="gallery-stat-card">
                        <div class="icon yellow">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <div class="text">
                            <h3>{{ $jumlahFoto }}</h3>
                            <span>Total Foto</span>
                        </div>
                    </div>
                </div>
                {{-- FILTER --}}
                <form method="GET"
                    action="{{ route('admin-galeri') }}"
                    class="filter-wrapper"
                    id="filterForm">
                    {{-- SEARCH --}}
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input type="text"
                            name="search"
                            id="searchInput"
                            placeholder="Cari galeri..."
                            value="{{ request('search') }}">
                    </div>
                    {{-- FILTER --}}
                    <div class="filter-group">
                        <div class="select-wrapper">
                            <select name="status" id="statusFilter">
                                <option value="">
                                    Semua Status
                                </option>
                                <option value="draft"
                                    {{ request('status') == 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>
                                <option value="publish"
                                    {{ request('status') == 'publish' ? 'selected' : '' }}>
                                    Publish
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- BUTTON TAMBAH --}}
                    <button type="button"
                            class="btn-add"
                            data-toggle="modal"
                            data-target="#modalTambahGaleri">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Galeri
                    </button>
                </form>
                {{-- GRID --}}
                <div class="gallery-admin-grid" id="galleryContainer">
                    {{-- CARD --}}
                    @foreach ($galeris as $galeri)
                        <div class="gallery-admin-card">
                            {{-- IMAGE --}}
                            <div class="gallery-image">
                                <img src="{{ asset('storage/' .$galeri->thumbnail)}}" alt="Thumbnail Galeri">
                                <div class="gallery-overlay">
                                    <span class="status">
                                        {{ ucfirst($galeri->status) }}
                                    </span>
                                </div>
                                <div class="gallery-overlay">
                                    <button class="action-btn" data-toggle="modal" data-target="#modalViewGaleri{{ $galeri->id }}">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button class="action-btn" data-toggle="modal" data-target="#modalEditGaleri{{ $galeri->id }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button class="action-btn delete" data-toggle="modal" data-target="#modalHapusGaleri{{ $galeri->id }}">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            {{-- CONTENT --}}
                            <div class="gallery-content">
                                <div class="gallery-top">
                                    <span class="gallery-date">
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($galeri->tanggal_kegiatan)->format('d M Y') }}
                                    </span>
                                    <div class="photo-count">
                                        <i class="fa-regular fa-images"></i>
                                        {{ $galeri->fotos->count() }} Foto
                                    </div>

                                </div>
                                <h3>
                                    {{ $galeri->judul }}
                                </h3>
                                <p>
                                    {{ Str::limit($galeri->deskripsi, 100) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="table-footer">
                        <!-- ROW -->
                        <div class="table-row-limit">
                            <span>
                                Tampilkan
                            </span>
                            <select id="rowsPerPage" onchange="location = '?per_page=' + this.value">
                                <option value="6" selected>6</option>
                                <option value="12">12</option>
                                <option value="18">18</option>
                                <option value="30">30</option>
                            </select>
                            <span>
                                data
                            </span>
                        </div>
                        <!-- INFO -->
                        <div class="table-info">
                            Menampilkan {{ $galeris->firstItem() }} - {{ $galeris->lastItem() }} dari {{ $galeris->total() }} data
                        </div>
                        <!-- PAGINATION -->
                        <div class="pagination-wrapper">
                            <a href="{{ $galeris->previousPageUrl() }}" class="pagination-btn">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                            <div class="pagination-number">
                                {{ $galeris->currentPage() }}
                            </div>
                            <a href="{{ $galeris->nextPageUrl() }}" class="pagination-btn">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                </div>
            </section>
        </div>
    </div>
    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const form =
                    document.getElementById("filterForm");
                const status =
                    document.getElementById("statusFilter");
                const search =
                    document.getElementById("searchInput");
                const container =
                    document.getElementById("galleryContainer");
                function loadGallery() {
                    const formData =
                        new FormData(form);
                    const params =
                        new URLSearchParams(formData);
                    fetch(form.action + '?' + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser =
                            new DOMParser();
                        const doc =
                            parser.parseFromString(
                                html,
                                'text/html'
                            );
                        const newContent =
                            doc.querySelector(
                                '#galleryContainer'
                            );
                        if (newContent) {
                            container.innerHTML =
                                newContent.innerHTML;
                        }
                    })
                    .catch(error => {
                        console.error(
                            'Filter Error:',
                            error
                        );
                    });
                }
                // FILTER STATUS
                status.addEventListener(
                    "change",
                    loadGallery
                );
                // SEARCH
                let timer;
                search.addEventListener(
                    "keyup",
                    function () {
                        clearTimeout(timer);
                        timer =
                            setTimeout(
                                loadGallery,
                                500
                            );
                    }
                );
            });
        </script>
    @endpush
@endsection
