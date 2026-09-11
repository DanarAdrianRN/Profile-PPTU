@extends('layout.app')

@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')

        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Hasil Tes'])

            <section class="hasil-tes">
                @include('components.archive-banner')
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
            document.addEventListener('DOMContentLoaded', function () {
                const picker = document.getElementById('santriNilaiPicker');
                const search = document.getElementById('santriNilaiSearch');
                const selectedId = document.getElementById('santriNilaiId');
                const list = document.getElementById('santriNilaiList');
                const empty = document.getElementById('santriNilaiEmpty');
                const options = Array.from(list.querySelectorAll('[role="option"]'));
                let visible = options;
                let active = -1;

                function highlight(index) {
                    active = index;
                    options.forEach(option => {
                        option.style.background = '';
                        option.setAttribute('aria-selected', String(option.dataset.value === selectedId.value));
                    });
                    search.removeAttribute('aria-activedescendant');
                    if (visible[active]) {
                        visible[active].style.background = '#e9ecef';
                        search.setAttribute('aria-activedescendant', visible[active].id);
                        visible[active].scrollIntoView({ block: 'nearest' });
                    }
                }

                function close() {
                    list.hidden = true;
                    search.setAttribute('aria-expanded', 'false');
                    highlight(-1);
                }

                function open() {
                    const query = selectedId.value ? '' : search.value.trim().toLocaleLowerCase('id');
                    visible = options.filter(option => {
                        const matches = option.textContent.toLocaleLowerCase('id').includes(query);
                        option.hidden = !matches;
                        return matches;
                    });
                    empty.hidden = visible.length > 0;
                    empty.textContent = options.length ? 'Tidak ada santri yang cocok.'
                        : 'Belum ada santri diterima yang belum memiliki nilai.';
                    list.hidden = false;
                    search.setAttribute('aria-expanded', 'true');
                    highlight(-1);
                }

                function choose(option) {
                    selectedId.value = option.dataset.value;
                    search.value = option.textContent.trim().replace(/\s+/g, ' ');
                    search.setCustomValidity('');
                    close();
                }

                search.addEventListener('focus', open);
                search.addEventListener('click', open);
                search.addEventListener('input', function () {
                    selectedId.value = '';
                    search.setCustomValidity('Pilih santri dari daftar yang tersedia.');
                    open();
                });
                search.addEventListener('keydown', function (event) {
                    if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                        event.preventDefault();
                        if (list.hidden) open();
                        if (visible.length) {
                            highlight(event.key === 'ArrowDown'
                                ? (active + 1) % visible.length
                                : (active <= 0 ? visible.length - 1 : active - 1));
                        }
                    } else if (event.key === 'Enter' && !list.hidden) {
                        event.preventDefault();
                        if (visible[active]) choose(visible[active]);
                    } else if (event.key === 'Escape') {
                        event.preventDefault();
                        close();
                    }
                });
                options.forEach(option => {
                    option.addEventListener('mousedown', event => event.preventDefault());
                    option.addEventListener('click', () => choose(option));
                });
                picker.addEventListener('focusout', function (event) {
                    if (!picker.contains(event.relatedTarget)) close();
                });
                document.addEventListener('click', function (event) {
                    if (!picker.contains(event.target)) close();
                });
                document.getElementById('formTambahNilai').addEventListener('submit', function (event) {
                    if (!selectedId.value) {
                        event.preventDefault();
                        search.setCustomValidity('Pilih santri dari daftar yang tersedia.');
                        search.reportValidity();
                    }
                });
            });

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