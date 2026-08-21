@extends('layout.app')

@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')

        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Manajemen Jadwal Pendaftaran'])

            <section class="admin-gelombang-table">
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text"
                            id="searchInput"
                            placeholder="Cari jadwal...">
                    </div>

                    <div class="filter-group">
                        <div class="select-wrapper">
                            <select id="statusFilter">
                                <option value="all">Semua Status</option>
                                <option value="publish">Publish</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn-add"
                        data-toggle="modal"
                        data-target="#modalTambahJadwal">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Jadwal
                    </button>
                </div>
                <div class="table-card">
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jadwal</th>
                                    <th>Periode</th>
                                    <th>Tanggal</th>
                                    <th>Urutan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
    
                            <tbody id="jadwalTableBody">
                                @forelse ($jadwals as $jadwal)
                                    <tr data-status="{{ $jadwal->is_publish ? 'publish' : 'draft' }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="gelombang-info">
                                                <h5>
                                                    {{ $jadwal->nama_jadwal }}
                                                </h5>
                                                <span>Jadwal alur pendaftaran</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $jadwal->periode?->nama_periode ?? 'Semua periode' }}
                                        </td>
                                        <td>
                                            {{ $jadwal->tanggal->translatedFormat('d F Y') }}
                                        </td>
                                        <td>{{ $jadwal->urutan }}</td>
                                        <td>
                                            <span class="status {{ $jadwal->is_publish ? 'active' : 'warning' }}">
                                                {{ $jadwal->is_publish ? 'Publish' : 'Draft' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-action">
                                                <button class="btn-action edit"
                                                    data-toggle="modal"
                                                    data-target="#modalEditJadwal{{ $jadwal->id }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
    
                                                <button class="btn-action delete"
                                                    data-toggle="modal"
                                                    data-target="#modalHapusJadwal{{ $jadwal->id }}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            Belum ada data jadwal pendaftaran
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
        id="modalTambahJadwal"
        tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="header-content">
                        <div>
                            <h3 class="modal-title-wrap">Tambah Jadwal</h3>
                            <span>Tambahkan jadwal alur pendaftaran</span>
                        </div>
                    </div>
                    <button type="button"
                        class="close-modal"
                        data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('jadwal-pendaftaran.store') }}"
                    method="POST"
                    id="formTambahJadwal">
                    @csrf
                    <div class="modal-body">
                        <div class="form-section">
                            <div class="section-title">
                                <h4>Informasi Jadwal</h4>
                            </div>

                            <div class="form-grid">
                                <div class="form-group full">
                                    <label>Nama Jadwal</label>
                                    <input type="text"
                                        name="nama_jadwal"
                                        value="{{ old('nama_jadwal') }}"
                                        placeholder="Tes Seleksi">
                                </div>

                                <div class="form-group">
                                    <label>Periode</label>
                                    <select name="periode_id">
                                        <option value="">Semua periode</option>
                                        @foreach ($periodes as $periode)
                                            <option value="{{ $periode->id }}"
                                                @selected(old('periode_id') == $periode->id)>
                                                {{ $periode->nama_periode }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date"
                                        name="tanggal"
                                        value="{{ old('tanggal') }}">
                                </div>

                                <div class="form-group">
                                    <label>Urutan</label>
                                    <input type="number"
                                        name="urutan"
                                        min="1"
                                        value="{{ old('urutan', 1) }}">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="is_publish">
                                        <option value="1">Publish</option>
                                        <option value="0">Draft</option>
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
                        form="formTambahJadwal"
                        class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Jadwal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($jadwals as $jadwal)
        <div class="modal fade admin-modal"
            id="modalEditJadwal{{ $jadwal->id }}"
            tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="header-content">
                            <div>
                                <h3 class="modal-title-wrap">Edit Jadwal</h3>
                                <span>Perbarui jadwal alur pendaftaran</span>
                            </div>
                        </div>
                        <button type="button"
                            class="close-modal"
                            data-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('jadwal-pendaftaran.update', $jadwal->id) }}"
                        method="POST"
                        id="formEditJadwal{{ $jadwal->id }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-section">
                                <div class="section-title">
                                    <h4>Informasi Jadwal</h4>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group full">
                                        <label>Nama Jadwal</label>
                                        <input type="text"
                                            name="nama_jadwal"
                                            value="{{ old('nama_jadwal', $jadwal->nama_jadwal) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Periode</label>
                                        <select name="periode_id">
                                            <option value="">Semua periode</option>
                                            @foreach ($periodes as $periode)
                                                <option value="{{ $periode->id }}"
                                                    @selected(old('periode_id', $jadwal->periode_id) == $periode->id)>
                                                    {{ $periode->nama_periode }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <input type="date"
                                            name="tanggal"
                                            value="{{ old('tanggal', $jadwal->tanggal->format('Y-m-d')) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Urutan</label>
                                        <input type="number"
                                            name="urutan"
                                            min="1"
                                            value="{{ old('urutan', $jadwal->urutan) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="is_publish">
                                            <option value="1" @selected($jadwal->is_publish)>Publish</option>
                                            <option value="0" @selected(! $jadwal->is_publish)>Draft</option>
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
                            form="formEditJadwal{{ $jadwal->id }}"
                            class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Jadwal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade delete-modal"
            id="modalHapusJadwal{{ $jadwal->id }}"
            tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content"
                    action="{{ route('jadwal-pendaftaran.destroy', $jadwal->id) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="delete-icon">
                        <i class="fa-regular fa-trash-can"></i>
                    </div>
                    <div class="delete-content">
                        <span class="delete-label">Konfirmasi Penghapusan</span>
                        <h3>Yakin ingin menghapus jadwal ini?</h3>
                        <p>Jadwal yang dihapus tidak akan tampil di landing page.</p>
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
                            Hapus Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @push('script')
        <script>
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tableBody = document.getElementById('jadwalTableBody');
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

            function filterJadwalTable() {
                const searchValue = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;

                filteredRows = [...allRows].filter(row => {
                    const matchSearch = row.innerText.toLowerCase().includes(searchValue);
                    const matchStatus = statusValue === 'all' || row.dataset.status === statusValue;
                    return matchSearch && matchStatus;
                });

                currentPage = 1;
                renderTable();
            }

            searchInput.addEventListener('keyup', filterJadwalTable);
            statusFilter.addEventListener('change', filterJadwalTable);

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