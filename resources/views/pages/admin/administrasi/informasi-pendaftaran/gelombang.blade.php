@extends('layout.app')

@section('content')
    <div class="admin-layout">
        {{-- SIDEBAR --}}
        @include('components.sidebar-admin')
        <div class="admin-main">
            {{-- HEADER --}}
            @include('components.header-admin', ['title' => 'Manajemen Gelombang Pendaftaran'])
            <section class="admin-gelombang-table">

                {{-- STATISTIK --}}
                <div class="gelombang-stats">
                    @foreach ($gelombangs->take(2) as $gelombang)
                        <div class="stat-card">
                            <div class="icon {{ $loop->first ? 'blue' : 'orange' }}">
                                <i class="fa-solid fa-wave-square"></i>
                            </div>
                            <div>
                                <span>
                                    Total Pendaftar {{ $gelombang->nama_gelombang }}
                                </span>
                                <h3>{{ $gelombang->pendaftarans_count }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- FILTER --}}
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text"
                            id="searchInput"
                            placeholder="Cari gelombang...">
                    </div>
                    <div class="filter-group">
                        <div class="select-wrapper">
                            <select id="categoryFilter">
                                <option value="all">
                                    Semua Status
                                </option>
                                <option value="draft">
                                    Draft
                                </option>
                                <option value="aktif">
                                    Aktif
                                </option>
                                <option value="ditutup">
                                    Selesai
                                </option>
                                <option value="akan_datang">
                                    Akan Datang
                                </option>
                            </select>
                        </div>
                    </div>
                    <button class="btn-add"
                            data-toggle="modal"
                            data-target="#modalTambahGelombang">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Gelombang
                    </button>
                </div>

                {{-- TABLE --}}
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gelombang</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>

                            </tr>

                        </thead>

                        <tbody id="biayaTableBody">

                            @forelse ($gelombangs as $gelombang)
                                <tr data-category="{{ $gelombang->is_publish ? $gelombang->status : 'draft' }}"
                                    data-jenjang="{{ \Carbon\Carbon::parse($gelombang->tanggal_mulai)->format('Y') }}">

                                    <td>

                                        {{ $loop->iteration }}

                                    </td>

                                    <td>

                                        <div class="gelombang-info">

                                            <h5>
                                                {{ $gelombang->nama_gelombang }}
                                            </h5>

                                            <span>

                                                Tahun Ajaran
                                                {{ \Carbon\Carbon::parse($gelombang->tanggal_mulai)->format('Y') }}/{{ \Carbon\Carbon::parse($gelombang->tanggal_selesai)->format('Y') }}

                                            </span>

                                        </div>

                                    </td>

                                    <td>

                                        {{ \Carbon\Carbon::parse($gelombang->tanggal_mulai)->translatedFormat('d F Y') }}

                                        <br>

                                        -

                                        {{ \Carbon\Carbon::parse($gelombang->tanggal_selesai)->translatedFormat('d F Y') }}

                                    </td>

                                    <td>

                                        @if (! $gelombang->is_publish)

                                            <span class="status warning">
                                                Draft
                                            </span>

                                        @elseif ($gelombang->status == 'aktif')

                                            <span class="status active">
                                                Aktif
                                            </span>

                                        @elseif ($gelombang->status == 'akan_datang')

                                            <span class="status warning">
                                                Akan Datang
                                            </span>

                                        @else

                                            <span class="status danger">
                                                Ditutup
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <div class="table-action">

                                            {{-- DETAIL --}}
                                            <button class="btn-action detail"
                                                    data-toggle="modal"
                                                    data-target="#modalViewGelombang{{$gelombang->id}}">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>

                                            {{-- EDIT --}}
                                            <button class="btn-action edit"
                                                    data-toggle="modal"
                                                    data-target="#modalEditGelombang{{$gelombang->id}}">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button class="btn-action delete"
                                                    data-toggle="modal"
                                                    data-target="#modalHapusGelombang{{$gelombang->id}}">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>


                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6">

                                        Belum ada data gelombang

                                    </td>

                                </tr>

                            @endforelse

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

            </section>
@push('script')
    <script>
        const tableBody = document.getElementById('biayaTableBody');
        const allRows = tableBody.querySelectorAll('tr');
        const rowsPerPageSelect =
            document.getElementById('rowsPerPage');
        const prevBtn =
            document.getElementById('prevPage');
        const nextBtn =
            document.getElementById('nextPage');
        const paginationNumber =
            document.getElementById('paginationNumber');
        const tableInfo =
            document.getElementById('tableInfo');
        // FILTER
        const searchInput =
            document.getElementById('searchInput');
        const categoryFilter =
            document.getElementById('categoryFilter');
        let currentPage = 1;
        let rowsPerPage =
            parseInt(rowsPerPageSelect.value);
        let filteredRows = [...allRows];
        // RENDER TABLE
        function renderTable() {
            const totalRows = filteredRows.length;
            const totalPages =
                Math.ceil(totalRows / rowsPerPage);
            const start =
                (currentPage - 1) * rowsPerPage;
            const end =
                start + rowsPerPage;
            allRows.forEach(row =>
                row.style.display = 'none');
            filteredRows.forEach((row, index) => {
                if (index >= start && index < end) {
                    row.style.display = '';
                }
            });
            paginationNumber.innerText =
                currentPage;
            if (totalRows > 0) {
                tableInfo.innerText =
                    `Menampilkan ${start + 1} - ${Math.min(end, totalRows)} dari ${totalRows} data`;
            } else {
                tableInfo.innerText =
                    `Data tidak ditemukan`;
            }
            prevBtn.disabled =
                currentPage === 1;
            nextBtn.disabled =
                currentPage === totalPages ||
                totalPages === 0;
        }
        // FILTER FUNCTION
        function filterTable() {
            const searchValue =
                searchInput.value.toLowerCase();
            const categoryValue =
                categoryFilter.value;
            filteredRows = [...allRows].filter(row => {
                const nama =
                    row.querySelector('.gelombang-info')
                    .innerText
                    .toLowerCase();
                const periode =
                    row.children[2]
                    .innerText
                    .toLowerCase();
                const category =
                    row.dataset.category;
                // SEARCH
                const matchSearch =
                    nama.includes(searchValue) ||
                    periode.includes(searchValue);

                // FILTER STATUS
                const matchCategory =
                    categoryValue === 'all' ||
                    category === categoryValue;

                return (
                    matchSearch &&
                    matchCategory
                );

            });

            currentPage = 1;

            renderTable();

        }

        // SEARCH
        searchInput.addEventListener(
            'keyup',
            filterTable
        );

        // FILTER
        categoryFilter.addEventListener(
            'change',
            filterTable
        );

        // ROW LIMIT
        rowsPerPageSelect.addEventListener(
            'change',
            function() {

                rowsPerPage =
                    parseInt(this.value);

                currentPage = 1;

                renderTable();

            }
        );

        // NEXT
        nextBtn.addEventListener(
            'click',
            function() {

                const totalPages =
                    Math.ceil(
                        filteredRows.length /
                        rowsPerPage
                    );

                if (currentPage < totalPages) {

                    currentPage++;

                    renderTable();

                }

            }
        );

        // PREV
        prevBtn.addEventListener(
            'click',
            function() {

                if (currentPage > 1) {

                    currentPage--;

                    renderTable();

                }

            }
        );

        renderTable();
    </script>
@endpush
@endsection
