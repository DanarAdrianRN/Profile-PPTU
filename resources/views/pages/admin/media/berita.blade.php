@extends('layout.app')
@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')
        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Manajemen Berita'])
            <section class="admin-berita">
                <div class="filter-wrapper">
                    <!-- SEARCH -->
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchInput" placeholder="Cari berita...">
                    </div>
                    <!-- FILTER -->
                    <div class="filter-group">
                        <div class="select-wrapper">
                            <select id="categoryFilter">
                                <option value="all">Semua Kategori</option>
                                <option value="Pendaftaran">Pendaftaran</option>
                                <option value="Prestasi">Prestasi</option>
                                <option value="Kegiatan">Kegiatan</option>
                                <option value="Pengumuman">Pengumuman</option>
                            </select>
                        </div>
                        <div class="select-wrapper">
                            <select id="statusFilter">
                                <option value="all">Semua Status</option>
                                <option value="Publish">Publish</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn-add" data-toggle="modal" data-target="#modalTambahBerita">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Berita
                    </button>
                </div>
                <!-- TABLE -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="berita-table" id="beritaTable">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Judul Berita</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="beritaTableBody">
                                @foreach($beritas as $berita)
                                    <tr data-category="{{ $berita->kategori }}"
                                    data-status="{{ $berita->status }}">
                                        <td>
                                            <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="Thumbnail Berita"
                                                class="thumb">
                                        </td>
                                        <td>
                                            <div class="news-title">

                                                <h4>
                                                    {{ $berita->judul }}
                                                </h4>
                                                <p>
                                                    {{ Str::limit(strip_tags($berita->isi_berita), 80) }}
                                                </p>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="category">
                                                {{ $berita->kategori }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status publish">
                                                {{ $berita->status }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $berita->created_at->format('d M Y') }}
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <button class="btn-action view" data-toggle="modal"
                                                    data-target="#modalViewBerita{{$berita->id}}">
                                                    <i class="fa-regular fa-eye"></i>
                                                </button>
                                                <button class="btn-action edit" type="button" data-toggle="modal"
                                                    data-target="#modalEditBerita{{ $berita->id }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button class="btn-action delete" data-toggle="modal"
                                                    data-target="#modalHapusBerita{{ $berita->id }}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- FOOTER TABLE -->
                    <div class="table-footer">
                        <!-- ROW -->
                        <div class="table-row-limit">
                            <span>
                                Tampilkan
                            </span>
                            <select id="rowsPerPage">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                            <span>
                                data
                            </span>
                        </div>
                        <!-- INFO -->
                        <div class="table-info" id="tableInfo">
                            Menampilkan 1 - 10 dari 20 data
                        </div>
                        <!-- PAGINATION -->
                        <div class="pagination-wrapper">
                            <button class="pagination-btn" id="prevPage">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <div class="pagination-number" id="paginationNumber">
                                1
                            </div>
                            <button class="pagination-btn" id="nextPage">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @push('script')
        <script>
            const tableBody = document.getElementById('beritaTableBody');
            const allRows = tableBody.querySelectorAll('tr');
            const rowsPerPageSelect = document.getElementById('rowsPerPage');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const paginationNumber = document.getElementById('paginationNumber');
            const tableInfo = document.getElementById('tableInfo');
            // FILTER
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const statusFilter = document.getElementById('statusFilter');
            let currentPage = 1;
            let rowsPerPage = parseInt(rowsPerPageSelect.value);
            let filteredRows = [...allRows];
            // RENDER TABLE
            function renderTable() {
                const totalRows = filteredRows.length;
                const totalPages = Math.ceil(totalRows / rowsPerPage);
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                allRows.forEach(row => row.style.display = 'none');
                filteredRows.forEach((row, index) => {
                    if (index >= start && index < end) {
                        row.style.display = '';
                    }
                });
                paginationNumber.innerText = currentPage;
                if (totalRows > 0) {
                    tableInfo.innerText =
                        `Menampilkan ${start + 1} - ${Math.min(end, totalRows)} dari ${totalRows} data`;
                } else {
                    tableInfo.innerText = `Data tidak ditemukan`;
                }
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled =
                    currentPage === totalPages || totalPages === 0;
            }
            // FILTER FUNCTION
            function filterTable() {

                const searchValue =
                    searchInput.value.toLowerCase();

                const categoryValue =
                    categoryFilter.value;

                const statusValue =
                    statusFilter.value;

                filteredRows = [...allRows].filter(row => {

                    const title =
                        row.querySelector('.news-title h4')
                        .innerText
                        .toLowerCase();

                    const category =
                        row.dataset.category;

                    const status =
                        row.dataset.status;

                    const matchSearch =
                        title.includes(searchValue);

                    const matchCategory =
                        categoryValue === 'all' ||
                        category === categoryValue;

                    const matchStatus =
                        statusValue === 'all' ||
                        status === statusValue;

                    return (
                        matchSearch &&
                        matchCategory &&
                        matchStatus
                    );

                });

                currentPage = 1;

                renderTable();
            }
            // SEARCH
            searchInput.addEventListener('keyup', filterTable);
            // FILTER
            categoryFilter.addEventListener('change', filterTable);
            statusFilter.addEventListener('change', filterTable);
            // ROW LIMIT
            rowsPerPageSelect.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
            });
            // NEXT
            nextBtn.addEventListener('click', function() {
                const totalPages =
                    Math.ceil(filteredRows.length / rowsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            });
            // PREV
            prevBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });
            renderTable();
        </script>
    @endpush
@endsection
