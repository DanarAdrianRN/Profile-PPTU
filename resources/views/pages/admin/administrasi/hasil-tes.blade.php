@extends('layout.app')

@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')

        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Hasil Tes'])

            <section class="hasil-tes">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari nama santri atau NISN..." id="searchInput">
                    </div>

                    <button class="btn-add" data-toggle="modal" data-target="#modalTambahNilai">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Nilai
                    </button>
                </div>

                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table-nilai" id="nilaiTable">
                            <thead>
                                <tr>
                                    <th>Nama Santri</th>
                                    <th>Baca Tulis Pegon</th>
                                    <th>Do'a Harian</th>
                                    <th>Ubudiyyah</th>
                                    <th>Membaca Al-Qur'an</th>
                                    <th>Hafalan Surat Pendek</th>
                                    <th>Wawancara</th>
                                    <th>Rata-rata</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="nilaiTableBody">
                                @forelse ($hasilTes as $hasil)
                                    <tr>
                                        <td>
                                            <div class="hasil-name">
                                                <h4>{{ $hasil->pendaftaran->nama_lengkap ?? '-' }}</h4>
                                                <span>NISN: {{ $hasil->pendaftaran->pendidikan->nisn ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $hasil->baca_tulis_pegon ?? '-' }}</td>
                                        <td>{{ $hasil->doa_harian ?? '-' }}</td>
                                        <td>{{ $hasil->ubudiyyah ?? '-' }}</td>
                                        <td>{{ $hasil->membaca_al_quran ?? '-' }}</td>
                                        <td>{{ $hasil->hafalan_surat_pendek ?? '-' }}</td>
                                        <td>{{ $hasil->wawancara ?? '-' }}</td>
                                        <td>{{ $hasil->rata_rata ?? '-' }}</td>
                                        <td>
                                            <div class="table-action">
                                                <button class="btn-action edit" data-toggle="modal"
                                                    data-target="#modalEditHasil{{ $hasil->id }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>

                                                <button class="btn-action delete" data-toggle="modal"
                                                    data-target="#modalHapusHasil{{ $hasil->id }}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada data hasil tes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <div class="table-row-limit">
                            <span>Tampilkan</span>
                            <select id="rowsPerPage">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                            <span>data</span>
                        </div>

                        <div class="table-info" id="tableInfo">
                            Menampilkan 0 data
                        </div>

                        <div class="pagination-wrapper">
                            <button class="pagination-btn" id="prevPage">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <div class="pagination-number" id="paginationNumber">1</div>
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
            const tableBody = document.getElementById('nilaiTableBody');
            const allRows = [...tableBody.querySelectorAll('tr')].filter(row => row.querySelector('.hasil-name'));
            const rowsPerPageSelect = document.getElementById('rowsPerPage');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const paginationNumber = document.getElementById('paginationNumber');
            const tableInfo = document.getElementById('tableInfo');
            const searchInput = document.getElementById('searchInput');
            let currentPage = 1;
            let rowsPerPage = parseInt(rowsPerPageSelect.value);
            let filteredRows = [...allRows];

            function renderTable() {
                const totalRows = filteredRows.length;
                const totalPages = Math.ceil(totalRows / rowsPerPage);
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                allRows.forEach(row => row.style.display = 'none');
                filteredRows.forEach((row, index) => {
                    row.style.display = index >= start && index < end ? '' : 'none';
                });

                paginationNumber.innerText = totalRows > 0 ? currentPage : 0;
                tableInfo.innerText = totalRows > 0
                    ? `Menampilkan ${start + 1} - ${Math.min(end, totalRows)} dari ${totalRows} data`
                    : 'Data tidak ditemukan';

                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;
            }

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();

                filteredRows = allRows.filter(row => {
                    const santri = row.querySelector('.hasil-name').innerText.toLowerCase();

                    return santri.includes(searchValue);
                });

                currentPage = 1;
                renderTable();
            }

            searchInput.addEventListener('keyup', filterTable);

            rowsPerPageSelect.addEventListener('change', function () {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
            });

            nextBtn.addEventListener('click', function () {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            });

            prevBtn.addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });

            renderTable();
        </script>
    @endpush
@endsection
