@extends('layout.app')

@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')

        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Data Admin'])
            <section class="guru-admin">
                @include('components.archive-banner')
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari admin..." id="searchInput">
                    </div>
                    <button class="btn-add" data-toggle="modal" data-target="#modalTambahAdmin">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Admin
                    </button>
                </div>
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table-guru" id="adminTable">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminTableBody">
                                @foreach ($admins as $admin)
                                    <tr>
                                        <td class="admin-search">
                                            <div class="guru-name">
                                                <h4>{{ $admin->nama_lengkap }}</h4>
                                            </div>
                                        </td>
                                        <td class="admin-search">{{ $admin->email }}</td>
                                        <td class="admin-search">{{ $admin->username }}</td>
                                        <td>
                                            <span class="badge-category madin">
                                                {{ ucfirst($admin->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-action">
                                                <button class="btn-action edit" data-toggle="modal"
                                                    data-target="#modalEditAdmin{{ $admin->id }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button class="btn-action delete" data-toggle="modal"
                                                    data-target="#modalHapusAdmin{{ $admin->id }}">
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

    <div class="modal fade admin-modal" id="modalTambahAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" action="{{ route('admin-data.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div class="modal-title-wrap">
                        <span>Manajemen Admin</span>
                        <h3>Tambah Admin</h3>
                    </div>
                    <button type="button" class="close-modal" data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-grid">
                        @include('pages.admin.administrasi.partials.form-admin', [
                            'formAdmin' => null,
                            'mode' => 'create',
                        ])
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($admins as $admin)
        <div class="modal fade admin-modal" id="modalEditAdmin{{ $admin->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form class="modal-content" action="{{ route('admin-data.update', $admin->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div class="modal-title-wrap">
                            <span>Manajemen Admin</span>
                            <h3>Edit Admin</h3>
                        </div>
                        <button type="button" class="close-modal" data-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-grid">
                            @include('pages.admin.administrasi.partials.form-admin', [
                                'formAdmin' => $admin,
                                'mode' => 'edit',
                            ])
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade delete-modal" id="modalHapusAdmin{{ $admin->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" action="{{ route('admin-data.destroy', $admin->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="delete-icon">
                        <i class="fa-regular fa-trash-can"></i>
                    </div>

                    <div class="delete-content">
                        <span class="delete-label">Konfirmasi Penghapusan</span>
                        <h3>Yakin ingin menghapus admin ini?</h3>
                        <p>Data admin yang sudah dihapus tidak dapat dikembalikan.</p>
                    </div>

                    <div class="delete-action">
                        <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-delete-confirm">
                            <i class="fa-solid fa-trash"></i>
                            Hapus Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('script')
    <script>
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('adminTableBody');
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
            const keyword = this.value.toLowerCase();

            filteredRows = [...allRows].filter(row => {
                const text = [...row.querySelectorAll('.admin-search')]
                    .map(cell => cell.innerText.toLowerCase())
                    .join(' ');
                return text.includes(keyword);
            });

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