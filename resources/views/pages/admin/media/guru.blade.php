@extends('layout.app')
@section('content')
    <div class="admin-layout">
        {{-- SIDEBAR --}}
        @include('components.sidebar-admin')
        <div class="admin-main">
            {{-- HEADER --}}
            @include('components.header-admin', ['title' => 'Guru & Ustadz'])
            <section class="guru-admin">
                {{-- STATISTIK --}}
                <div class="guru-stat-grid">
                    <div class="guru-stat-card">
                        <div class="icon blue">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="info">
                            <h3>{{$totalPengajar}}</h3>
                            <p>Total Pengajar</p>
                        </div>
                    </div>
                    <div class="guru-stat-card">
                        <div class="icon green">
                            <i class="fa-solid fa-school"></i>
                        </div>
                        <div class="info">
                            <h3>{{$guruSMP}}</h3>
                            <p>Guru SMP</p>
                        </div>
                    </div>
                    <div class="guru-stat-card">
                        <div class="icon orange">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div class="info">
                            <h3>{{$guruSMK}}</h3>
                            <p>Guru SMK</p>
                        </div>
                    </div>
                    <div class="guru-stat-card">
                        <div class="icon purple">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div class="info">
                            <h3>{{$ustadzMadin}}</h3>
                            <p>Ustadz Madin</p>
                        </div>
                    </div>
                    <div class="guru-stat-card">
                        <div class="icon blue">
                            <i class="fa-solid fa-book-quran"></i>
                        </div>
                        <div class="info">
                            <h3>{{$ustadzMadqur}}</h3>
                            <p>Ustadz Madqur</p>
                        </div>
                    </div>
                    <div class="guru-stat-card">
                        <div class="icon green">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                        <div class="info">
                            <h3>{{$ustadzTPQ}}</h3>
                            <p>Ustadz TPQ</p>
                        </div>
                    </div>
                </div>
                {{-- FILTER --}}
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari nama guru atau ustadz..." id="searchInput">
                    </div>
                    <div class="filter-group">
                        <div class="select-wrapper">
                            <select id="categoryFilter">
                                <option value="all">Semua Kategori</option>
                                @foreach($kategoriGuru as $kategori)
                                    <option value="{{ $kategori }}">{{ $kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="select-wrapper">
                            <select id="statusFilter">
                                <option value="all">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <button class="btn-add" data-toggle="modal" data-target="#modalTambahGuru">
                            <i class="fa-solid fa-plus"></i>
                            Tambah Guru
                        </button>
                    </div>
                </div>
                {{-- TABLE --}}
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table-guru" id="guruTable">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Guru</th>
                                    <th>Kategori</th>
                                    <th>Mapel / Bidang</th>
                                    <th>Pendidikan</th>
                                    <th>Status</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="guruTableBody">
                                @foreach( $gurus as $guru)
                                    <tr data-category="{{ $guru->kategori }}"
                                    data-status="{{ $guru->status }}">
                                        <td>
                                            <div class="guru-photo">
                                                <img src="{{ asset ('storage/' . $guru->foto) }}" alt="Foto Guru">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="guru-name">
                                                <h4>
                                                    {{$guru->nama_lengkap}}
                                                </h4>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-category madin">
                                                {{$guru->kategori}}
                                            </span>
                                        </td>
                                        <td class="guru-mapel">
                                            {{$guru->mapel_bidang}}
                                        </td>
                                        <td class="guru-pendidikan">
                                            {{$guru->pendidikan}}
                                        </td>
                                        <td>
                                            <span class="badge-status active">
                                                {{$guru->status}}
                                            </span>
                                        </td>
                                        <td class="guru-alamat">
                                            {{$guru->alamat}}
                                        </td>
                                        <td>
                                            <div class="table-action">
                                                <button class="btn-action view" data-toggle="modal"
                                                    data-target="#modalViewGuru{{$guru->id}}">
                                                    <i class="fa-regular fa-eye"></i>
                                                </button>
                                                <button class="btn-action edit" data-toggle="modal"
                                                    data-target="#modalEditGuru{{$guru->id}}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button class="btn-action delete" data-toggle="modal"
                                                    data-target="#modalHapusGuru{{$guru->id}}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
            const tableBody = document.getElementById('guruTableBody');
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

                    const nama =
                        row.querySelector('.guru-name h4')
                        .innerText
                        .toLowerCase();

                    const mapel =
                        row.querySelector('.guru-mapel')
                        .innerText
                        .toLowerCase();

                    const pendidikan =
                        row.querySelector('.guru-pendidikan')
                        .innerText
                        .toLowerCase();

                    const alamat =
                        row.querySelector('.guru-alamat')
                        .innerText
                        .toLowerCase();

                    const category =
                        row.dataset.category;

                    const status =
                        row.dataset.status;

                    // SEARCH MULTI KOLOM
                    const matchSearch =
                        nama.includes(searchValue) ||
                        mapel.includes(searchValue) ||
                        pendidikan.includes(searchValue) ||
                        alamat.includes(searchValue);

                    // FILTER KATEGORI
                    const matchCategory =
                        categoryValue === 'all' ||
                        category === categoryValue;

                    // FILTER STATUS
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
