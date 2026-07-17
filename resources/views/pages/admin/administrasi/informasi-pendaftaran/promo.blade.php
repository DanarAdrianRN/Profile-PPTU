@extends('layout.app')

@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')

        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Manajemen Promo Pendaftaran'])

            <section class="admin-gelombang-table">
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text"
                            id="searchInput"
                            placeholder="Cari promo...">
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
                            <select id="statusFilter">
                                <option value="all">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn-add"
                        data-toggle="modal"
                        data-target="#modalTambahPromo">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Promo
                    </button>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Promo</th>
                                <th>Gelombang</th>
                                <th>Jenjang</th>
                                <th>Biaya</th>
                                <th>Nilai</th>
                                <th>Kuota</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="promoTableBody">
                            @forelse ($promos as $promo)
                                @php
                                    $nilaiPromo = match ($promo->tipe) {
                                        'persentase' => $promo->nilai . '%',
                                        'gratis_biaya' => 'Gratis Biaya',
                                        default => 'Rp ' . number_format($promo->nilai, 0, ',', '.'),
                                    };
                                @endphp

                                <tr data-jenjang="{{ $promo->jenjang ?? 'all' }}"
                                    data-status="{{ $promo->is_active ? 'aktif' : 'nonaktif' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="gelombang-info">
                                            <h5>{{ $promo->nama_promo }}</h5>
                                            <span>{{ $promo->keterangan ?? 'Tidak ada deskripsi' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $promo->gelombangPendaftaran?->nama_gelombang ?? 'Semua gelombang akan datang' }}
                                    </td>
                                    <td>{{ $promo->jenjang ?? 'Semua' }}</td>
                                    <td>
                                        @if ($promo->pembayarans->count())
                                            {{ $promo->pembayarans->pluck('nama_pembayaran')->join(', ') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="diskon-badge">
                                            {{ $nilaiPromo }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $promo->kuota ? $promo->kuota . ' pendaftar' : 'Tanpa batas' }}
                                    </td>
                                    <td>
                                        <span class="status {{ $promo->is_active ? 'active' : 'danger' }}">
                                            {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-action">
                                            <button class="btn-action detail"
                                                data-toggle="modal"
                                                data-target="#modalViewPromo{{ $promo->id }}">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>
                                            <button class="btn-action edit"
                                                data-toggle="modal"
                                                data-target="#modalEditPromo{{ $promo->id }}">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button class="btn-action delete"
                                                data-toggle="modal"
                                                data-target="#modalHapusPromo{{ $promo->id }}">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">Belum ada data promo</td>
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
                        Menampilkan data promo
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
            </section>
        </div>
    </div>

    <div class="modal fade admin-modal"
        id="modalTambahPromo"
        tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="header-content">
                        <div>
                            <h3 class="modal-title-wrap">Tambah Promo</h3>
                            <span>Atur cakupan, biaya, dan nilai promo pendaftaran</span>
                        </div>
                    </div>
                    <button type="button"
                        class="close-modal"
                        data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('promo.store') }}"
                    method="POST"
                    id="formTambahPromo"
                    class="promo-form">
                    @csrf
                    <div class="modal-body">
                        @include('pages.admin.administrasi.informasi-pendaftaran.partials.form-promo', [
                            'promo' => null,
                            'promoGelombangs' => $promoGelombangs,
                            'promoPembayarans' => $promoPembayarans,
                        ])
                    </div>
                </form>

                <div class="modal-footer">
                    <button type="button"
                        class="btn-cancle"
                        data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit"
                        form="formTambahPromo"
                        class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Promo
                    </button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($promos as $promo)
        @php
            $nilaiPromo = match ($promo->tipe) {
                'persentase' => $promo->nilai . '%',
                'gratis_biaya' => 'Gratis Biaya',
                default => 'Rp ' . number_format($promo->nilai, 0, ',', '.'),
            };
        @endphp

        <div class="modal fade admin-modal"
            id="modalViewPromo{{ $promo->id }}"
            tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content gelombang-view">
                    <div class="modal-header">
                        <div class="header-content">
                            <div>
                                <h3 class="modal-title-wrap">Detail Promo</h3>
                                <span>Lihat informasi promo pendaftaran</span>
                            </div>
                        </div>
                        <button type="button"
                            class="close-modal"
                            data-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="jadwal-card {{ $promo->is_active ? 'active' : 'danger' }}">
                            <span class="badge">
                                {{ $promo->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                            <h4>{{ $promo->nama_promo }}</h4>
                            <div class="tanggal">
                                <i class="fa-regular fa-calendar"></i>
                                {{ $promo->gelombangPendaftaran?->nama_gelombang ?? 'Semua gelombang akan datang' }}
                            </div>
                            <div class="diskon">
                                <i class="fa-solid fa-tag"></i>
                                {{ $nilaiPromo }}
                            </div>
                            <p>{{ $promo->keterangan ?? 'Tidak ada deskripsi promo' }}</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                            class="btn-cancle"
                            data-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade admin-modal"
            id="modalEditPromo{{ $promo->id }}"
            tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="header-content">
                            <div>
                                <h3 class="modal-title-wrap">Edit Promo</h3>
                                <span>Perbarui cakupan, biaya, dan nilai promo</span>
                            </div>
                        </div>
                        <button type="button"
                            class="close-modal"
                            data-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('promo.update', $promo->id) }}"
                        method="POST"
                        id="formEditPromo{{ $promo->id }}"
                        class="promo-form">
                        @csrf
                        <div class="modal-body">
                            @include('pages.admin.administrasi.informasi-pendaftaran.partials.form-promo', [
                                'promo' => $promo,
                                'promoGelombangs' => $promoGelombangs,
                                'promoPembayarans' => $promoPembayarans,
                            ])
                        </div>
                    </form>

                    <div class="modal-footer">
                        <button type="button"
                            class="btn-cancle"
                            data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            form="formEditPromo{{ $promo->id }}"
                            class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Promo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade delete-modal"
            id="modalHapusPromo{{ $promo->id }}"
            tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content"
                    action="{{ route('promo.destroy', $promo->id) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="delete-icon">
                        <i class="fa-regular fa-trash-can"></i>
                    </div>
                    <div class="delete-content">
                        <span class="delete-label">Konfirmasi Penghapusan</span>
                        <h3>Yakin ingin menghapus promo ini?</h3>
                        <p>Promo yang sudah dihapus tidak dapat dikembalikan lagi.</p>
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
                            Hapus Promo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @push('script')
        <script>
            const tableBody = document.getElementById('promoTableBody');
            const allRows = tableBody.querySelectorAll('tr');
            const rowsPerPageSelect = document.getElementById('rowsPerPage');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const paginationNumber = document.getElementById('paginationNumber');
            const tableInfo = document.getElementById('tableInfo');
            const searchInput = document.getElementById('searchInput');
            const jenjangFilter = document.getElementById('jenjangFilter');
            const statusFilter = document.getElementById('statusFilter');
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
                    if (index >= start && index < end) {
                        row.style.display = '';
                    }
                });

                paginationNumber.innerText = currentPage;
                tableInfo.innerText = totalRows > 0
                    ? `Menampilkan ${start + 1} - ${Math.min(end, totalRows)} dari ${totalRows} data`
                    : 'Data tidak ditemukan';
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;
            }

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();
                const jenjangValue = jenjangFilter.value;
                const statusValue = statusFilter.value;

                filteredRows = [...allRows].filter(row => {
                    const rowText = row.innerText.toLowerCase();
                    const matchSearch = rowText.includes(searchValue);
                    const matchJenjang = jenjangValue === 'all' ||
                        row.dataset.jenjang === jenjangValue ||
                        row.dataset.jenjang === 'all';
                    const matchStatus = statusValue === 'all' ||
                        row.dataset.status === statusValue;

                    return matchSearch && matchJenjang && matchStatus;
                });

                currentPage = 1;
                renderTable();
            }

            document.querySelectorAll('.promo-form').forEach(form => {
                const gelombangMode = form.querySelector('[name="cakupan_gelombang"]');
                const gelombangSelect = form.querySelector('[data-gelombang-field]');
                const biayaMode = form.querySelector('[name="cakupan_biaya"]');
                const biayaList = form.querySelector('[data-biaya-list]');
                const tipe = form.querySelector('[name="tipe"]');
                const nilai = form.querySelector('[name="nilai"]');

                function syncFields() {
                    gelombangSelect.style.display = gelombangMode.value === 'satu' ? '' : 'none';
                    biayaList.style.display = biayaMode.value === 'satu' ? '' : 'none';
                    nilai.closest('.form-group').style.display = tipe.value === 'gratis_biaya' ? 'none' : '';
                }

                gelombangMode.addEventListener('change', syncFields);
                biayaMode.addEventListener('change', syncFields);
                tipe.addEventListener('change', syncFields);
                syncFields();
            });

            searchInput.addEventListener('keyup', filterTable);
            jenjangFilter.addEventListener('change', filterTable);
            statusFilter.addEventListener('change', filterTable);
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
