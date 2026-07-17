@extends('layout.app')

@section('content')
    <div class="admin-layout">
        {{-- SIDEBAR --}}
        @include('components.sidebar-admin')
        <div class="admin-main">
            {{-- HEADER --}}
            @include('components.header-admin', ['title' => 'Manajemen Biaya Pendaftaran'])
            <div class="admin-biaya-pendaftaran">
                {{-- FILTER --}}
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari biaya pendaftaran..." id="searchInput">
                    </div>
                    <div class="filter-group">
                        <div class="select-wrapper">
                        <select id="jenjangFilter">
                            <option value="all">Semua Jenjang</option>
                            <option value="SMP">SMP</option>
                            <option value="SMK">SMK</option>
                        </select>
                        </div>
                        <div class="select-wrapper">
                            <select id="categoryFilter">
                                <option value="all">Semua</option>
                                <option value="Biaya Tahunan">Tahunan</option>
                                <option value="Biaya Bulanan">Bulanan</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn-add" data-toggle="modal" data-target="#modalTambahBiaya">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Biaya
                    </button>
                </div>
                {{-- TABLE --}}
                <div class="table-wrapper">
                    <table id="biayaTable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Jenjang</th>
                                <th>Nama Biaya</th>
                                <th>Kategori</th>
                                <th>Total</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="biayaTableBody">
                            @forelse ($pembayarans as $item)
                                <tr data-category="{{ $item->kategori }}"
                                    data-jenjang="{{ $item->jenjang }}">
                                    {{-- NOMOR --}}
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    {{-- JENJANG --}}
                                    <td>
                                        <div class="jenjang-badge 
                                            {{ strtolower($item->jenjang) }}">
                                            {{ $item->jenjang }}
                                        </div>
                                    </td>
                                    {{-- NAMA BIAYA --}}
                                    <td>
                                        <div class="biaya-info">
                                            <h5>
                                                {{ $item->nama_pembayaran }}
                                            </h5>
                                        </div>
                                    </td>
                                    {{-- KATEGORI --}}
                                    <td>
                                        <span class="kategori
                                            {{ $item->kategori == 'Biaya Tahunan' ? 'awal' : 'bulanan' }}">
                                            {{ $item->kategori }}
                                        </span>
                                    </td>
                                    {{-- NOMINAL --}}
                                    <td class="biaya-nominal">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>
                                    {{-- AKSI --}}
                                    <td>
                                        <div class="table-action">
                                            {{-- VIEW --}}
                                            <button
                                                class="btn-action view"
                                                data-toggle="modal"
                                                data-target="#modalViewBiaya{{ $item->id }}">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>
                                            {{-- EDIT --}}
                                            <button
                                                class="btn-action edit"
                                                data-toggle="modal"
                                                data-target="#modalEditBiaya{{ $item->id }}">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            {{-- DELETE --}}
                                            <button
                                                class="btn-action delete"
                                                data-toggle="modal"
                                                data-target="#modalHapus">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        Data pembayaran belum tersedia
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
            </div>
        </div>
    </div>
    @push('script')
        <script>
            const tableBody = document.getElementById('biayaTableBody');
            const allRows = tableBody.querySelectorAll('tr');
            const rowsPerPageSelect = document.getElementById('rowsPerPage');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const paginationNumber = document.getElementById('paginationNumber');
            const tableInfo = document.getElementById('tableInfo');
            // FILTER
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const jenjangFilter = document.getElementById('jenjangFilter');
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

                const jenjangValue =
                    jenjangFilter.value;

                filteredRows = [...allRows].filter(row => {

                    const nama =
                        row.querySelector('.biaya-info')
                        .innerText
                        .toLowerCase();

                    const nip =
                        row.querySelector('.biaya-nominal')
                        .innerText
                        .toLowerCase();

                    const category =
                        row.dataset.category;

                    const jenjang =
                        row.dataset.jenjang;
                    // SEARCH MULTI KOLOM
                    const matchSearch =
                        nama.includes(searchValue) ||
                        nip.includes(searchValue);

                    // FILTER KATEGORI
                    const matchCategory =
                        categoryValue === 'all' ||
                        category === categoryValue;

                    // FILTER JENJANG
                    const matchJenjang =
                        jenjangValue === 'all' ||
                        jenjang === jenjangValue;

                    return (
                        matchSearch &&
                        matchCategory &&
                        matchJenjang
                    );

                });

                currentPage = 1;

                renderTable();
            }
            // SEARCH
            searchInput.addEventListener('keyup', filterTable);
            // FILTER
            categoryFilter.addEventListener('change', filterTable);
            jenjangFilter.addEventListener('change', filterTable);
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
