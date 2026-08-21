@extends('layout.app')

@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')

        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Manajemen Periode Pendaftaran'])

            <section class="admin-gelombang-table">
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text"
                            id="searchInput"
                            placeholder="Cari periode...">
                    </div>

                    <button class="btn-add"
                        data-toggle="modal"
                        data-target="#modalTambahPeriode">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Periode
                    </button>
                </div>
                <div class="table-card">
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Periode</th>
                                    <th>Pendaftar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
    
                            <tbody id="periodeTableBody">
                                @forelse ($periodes as $periode)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="gelombang-info">
                                                <h5>{{ $periode->nama_periode }}</h5>
                                                <span>
                                                    {{ $periode->is_active ? 'Periode yang sedang menerima perubahan data' : 'Periode arsip' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>{{ $periode->pendaftarans_count }} data</td>
                                        <td>
                                            <span class="status {{ $periode->is_active ? 'active' : 'danger' }}">
                                                {{ $periode->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                            @if (session('viewing_periode_id') == $periode->id)
                                                <span class="status active" style="margin-left: 6px;">
                                                    Sedang Dilihat
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-action">
                                                <a class="btn-action view"
                                                    href="{{ route('periode.lihat-data', $periode->id) }}"
                                                    title="Lihat Data Periode Ini">
                                                    <i class="fa-solid fa-folder-open"></i>
                                                </a>
    
                                                <button class="btn-action edit"
                                                    data-toggle="modal"
                                                    data-target="#modalEditPeriode{{ $periode->id }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
    
                                                @if (! $periode->is_active)
                                                    <button class="btn-action delete"
                                                        data-toggle="modal"
                                                        data-target="#modalHapusPeriode{{ $periode->id }}">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            Belum ada data periode
                                        </td>
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
                            Menampilkan data
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

    <div class="modal fade admin-modal"
        id="modalTambahPeriode"
        tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="header-content">
                        <div>
                            <h3 class="modal-title-wrap">Tambah Periode</h3>
                            <span>Tambahkan periode pendaftaran baru</span>
                        </div>
                    </div>
                    <button type="button"
                        class="close-modal"
                        data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('periode.store') }}"
                    method="POST"
                    id="formTambahPeriode">
                    @csrf
                    <div class="modal-body">
                        <div class="form-section">
                            <div class="section-title">
                                <h4>Informasi Periode</h4>
                            </div>

                            <div class="form-grid">
                                <div class="form-group full">
                                    <label>Nama Periode</label>
                                    <input type="text"
                                        name="nama_periode"
                                        value="{{ old('nama_periode') }}"
                                        placeholder="Pendaftaran Santri Baru Tahun Ajaran 2026-2027">
                                </div>

                                <div class="form-group">
                                    <label>Status Periode</label>
                                    <select name="is_active">
                                        <option value="0">Nonaktif</option>
                                        <option value="1">Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="modal-footer">
                    <button type="button"
                        class="btn-cancle"
                        data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit"
                        form="formTambahPeriode"
                        class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Periode
                    </button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($periodes as $periode)
        <div class="modal fade admin-modal"
            id="modalEditPeriode{{ $periode->id }}"
            tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="header-content">
                            <div>
                                <h3 class="modal-title-wrap">Edit Periode</h3>
                                <span>Perbarui nama dan status periode</span>
                            </div>
                        </div>
                        <button type="button"
                            class="close-modal"
                            data-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('periode.update', $periode->id) }}"
                        method="POST"
                        id="formEditPeriode{{ $periode->id }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-section">
                                <div class="section-title">
                                    <h4>Informasi Periode</h4>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group full">
                                        <label>Nama Periode</label>
                                        <input type="text"
                                            name="nama_periode"
                                            value="{{ old('nama_periode', $periode->nama_periode) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Status Periode</label>
                                        <select name="is_active">
                                            <option value="0" @selected(! $periode->is_active)>Nonaktif</option>
                                            <option value="1" @selected($periode->is_active)>Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="modal-footer">
                        <button type="button"
                            class="btn-cancle"
                            data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            form="formEditPeriode{{ $periode->id }}"
                            class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Periode
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if (! $periode->is_active)
            <div class="modal fade delete-modal"
                id="modalHapusPeriode{{ $periode->id }}"
                tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content"
                        action="{{ route('periode.destroy', $periode->id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="delete-icon">
                            <i class="fa-regular fa-trash-can"></i>
                        </div>
                        <div class="delete-content">
                            <span class="delete-label">Konfirmasi Penghapusan</span>
                            <h3>Yakin ingin menghapus periode ini?</h3>
                            <p>Data pendaftaran pada periode ini tidak ikut dihapus.</p>
                        </div>
                        <div class="delete-action">
                            <button type="button"
                                class="btn-cancel"
                                data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit"
                                class="btn-delete-confirm">
                                <i class="fa-solid fa-trash"></i>
                                Hapus Periode
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    @push('script')
        <script>
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('periodeTableBody');
            const allRows = tableBody.querySelectorAll('tr');
            const rowsPerPageSelect = document.getElementById('rowsPerPage');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const paginationNumber = document.getElementById('paginationNumber');
            const tableInfo = document.getElementById('tableInfo');

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
                    if (index >= start && index < end) row.style.display = '';
                });

                paginationNumber.innerText = currentPage;

                tableInfo.innerText = totalRows > 0
                    ? `Menampilkan ${start + 1} - ${Math.min(end, totalRows)} dari ${totalRows} data`
                    : `Data tidak ditemukan`;

                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;
            }

            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                filteredRows = [...allRows].filter(row =>
                    row.innerText.toLowerCase().includes(searchValue)
                );
                currentPage = 1;
                renderTable();
            });

            rowsPerPageSelect.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
            });

            nextBtn.addEventListener('click', function() {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            });

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