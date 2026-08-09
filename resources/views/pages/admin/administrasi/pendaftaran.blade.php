@extends('layout.app')

@section('content')
    <div class="admin-layout">
        {{-- SIDEBAR --}}
        @include('components.sidebar-admin')
        <div class="admin-main">
            {{-- HEADER --}}
            @include('components.header-admin', ['title' => 'Manajemen Pendaftaran'])
            <section class="pendaftaran-admin">
                @include('components.archive-banner')

                {{-- STATISTICS --}}
                <div class="pendaftaran-stats">
                    <div class="stat-card">
                        <div class="icon blue">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="info">
                            <span>
                                Total Pendaftar
                            </span>
                            <h3>
                                {{ $jumlahPendaftaran }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon yellow">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="info">
                            <span>
                                Menunggu Verifikasi
                            </span>
                            <h3>
                                {{ $menungguVerifikasi }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon green">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div class="info">
                            <span>
                                Diterima
                            </span>
                            <h3>
                                {{ $diterima }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon red">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <div class="info">
                            <span>
                                Ditolak
                            </span>
                            <h3>
                                {{ $ditolak }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon teal">
                            <i class="fa-money-bill-wave"></i>
                        </div>
                        <div class="info">
                            <span>
                                Lunas
                            </span>
                            <h3>
                                {{ $lunas }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon orange">
                            <i class="fa-money-bill-wave"></i>
                        </div>
                        <div class="info">
                            <span>
                                Belum Lunas
                            </span>
                            <h3>
                                {{ $belumLunas }}
                            </h3>
                        </div>
                    </div>
                </div>
                {{-- FILTER BAR --}}
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchInput" placeholder="Cari nama pendaftar...">
                    </div>
                    <div class="filter-group">
                        <div class="select-wrapper">
                            <select id="statusFilter">
                                <option value="all">Semua Status</option>
                                <option value="belum_bayar">Belum Bayar</option>
                                <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="select-wrapper">
                            <select id="jenjangFilter">
                                <option value="all">Semua Jenjang</option>
                                <option value="SMP">SMP</option>
                                <option value="SMK">SMK</option>
                            </select>
                        </div>
                        <div class="select-wrapper">
                        <select id="paymentFilter">
                            <option value="all">Semua Pembayaran</option>
                            <option value="unpaid">Belum Lunas</option>
                            <option value="paid">Lunas</option>
                        </select>
                        </div>
                    </div>
                    <div class="export-dropdown">
                        <button class="btn-add" type="button" onclick="document.getElementById('exportMenuPendaftaran').classList.toggle('show')">
                            <i class="fa-solid fa-file-export"></i>
                            Export
                        </button>
                        <div class="export-dropdown-menu" id="exportMenuPendaftaran">
                            <a href="#" onclick="exportPendaftaran('xlsx'); return false;">
                                <i class="fa-solid fa-file-excel"></i> Excel (.xlsx)
                            </a>
                            <a href="#" onclick="exportPendaftaran('pdf'); return false;">
                                <i class="fa-solid fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
                {{-- TABLE --}}
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="pendaftaran-table">
                            <thead>
                                <tr>

                                    <th>No</th>

                                    <th>Pendaftar</th>

                                    <th>Jenjang</th>

                                    <th>Jurusan</th>

                                    <th>Wali Santri</th>

                                    <th>Pembayaran</th>
                                    <th>Dokumen</th>

                                    <th>Tanggal Daftar</th>

                                    <th>Status</th>

                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody id="pendaftaranTableBody">
                            @foreach ($pendaftarans as $key => $pendaftaran)
                                @php
                                    $tagihanRow = $pendaftaran->tagihanSantri;
                                    $totalTagihanRow = $tagihanRow?->details?->count() ?? 0;
                                    $totalLunasRow = $tagihanRow?->details
                                        ?->where('status_pembayaran', 'lunas')
                                        ->count() ?? 0;
                                    $isLunasRow = $totalTagihanRow > 0 && $totalTagihanRow === $totalLunasRow;
                                @endphp
                                <tr
                                    data-jenjang="{{ $pendaftaran->pendidikan->jenjang_pendidikan ?? '' }}"
                                    data-status="{{ $pendaftaran->status }}"
                                    data-payment="{{ $isLunasRow ? 'paid' : 'unpaid' }}"
                                >
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>

                                        <div class="student-cell">

                                            <div class="student-photo">

                                                @php
                                                    $foto = $pendaftaran->dokumens
                                                        ->where('jenis_dokumen', 'Foto Warna')
                                                        ->first();
                                                @endphp

                                                <img src="{{ $foto ? asset('storage/' . $foto->file) : asset('assets/galeri1.jpg') }}"
                                                    alt="{{ $pendaftaran->nama_lengkap }}">

                                            </div>

                                            <div class="student-info">

                                                <h5>
                                                    {{ $pendaftaran->nama_lengkap }}
                                                </h5>

                                                <span>
                                                    NISN :
                                                    {{ $pendaftaran->pendidikan->nisn ?? '-' }}
                                                </span>

                                            </div>

                                        </div>

                                    </td>
                                    <td>

                                        <span class="badge blue">
                                            {{ $pendaftaran->pendidikan->jenjang_pendidikan ?? '-' }}
                                        </span>

                                    </td>
                                    <td>
                                        {{ $pendaftaran->pendidikan->jurusan ?? '-' }}
                                    </td>
                                    <td>

                                        @php
                                            $ayah = $pendaftaran->orangTuas->where('tipe', 'ayah')->first();
                                        @endphp

                                        {{ $ayah->nama ?? '-' }}

                                    </td>
                                    <td>
                                        @php
                                            $tagihan = $pendaftaran->tagihanSantri;

                                            $totalTagihan = $tagihan?->details?->count() ?? 0;

                                            $totalLunas = $tagihan?->details
                                                ?->where('status_pembayaran', 'lunas')
                                                ->count() ?? 0;

                                            $isLunas = $totalTagihan > 0 && $totalTagihan === $totalLunas;
                                        @endphp

                                        <button
                                            class="payment-badge {{ $isLunas ? 'paid' : 'unpaid' }}"
                                            data-toggle="modal"
                                            data-target="#modalBayar{{ $pendaftaran->id }}"
                                        >
                                            {{ $isLunas ? 'Lunas' : 'Belum Lunas' }}
                                        </button>
                                    </td>
                                    <td>
                                        <button
                                            class="btn-action view"
                                            data-toggle="modal"
                                            data-target="#modalDokumen{{ $pendaftaran->id }}"
                                            title="Lihat Dokumen ({{ $pendaftaran->dokumens->count() }})"
                                        >
                                            <i class="fa-solid fa-folder-open"></i>
                                        </button>
                                    </td>
                                    <td>
                                        {{ $pendaftaran->created_at->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                        <div class="status-dropdown">

                                            @php
                                                $statusBadgeClass = in_array($pendaftaran->status, ['belum_bayar', 'ditolak'])
                                                    ? 'unpaid'
                                                    : 'paid';
                                            @endphp
                                            <button class="payment-badge {{ $statusBadgeClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $pendaftaran->status)) }}
                                            </button>

                                            @if ($canManageSelectedPeriod)
                                                <div class="status-dropdown-menu">

                                                    <form
                                                        action="{{ route('pendaftaran.update-status', $pendaftaran->id) }}"
                                                        method="POST">

                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            name="status"
                                                            value="menunggu_verifikasi">

                                                            Menunggu Verifikasi

                                                        </button>

                                                        <button
                                                            type="submit"
                                                            name="status"
                                                            value="diterima">

                                                            Diterima

                                                        </button>

                                                        <button
                                                            type="submit"
                                                            name="status"
                                                            value="ditolak">

                                                            Ditolak

                                                        </button>

                                                    </form>

                                                </div>
                                            @endif

                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-action">
                                            <button class="btn-action view" data-toggle="modal"
                                                data-target="#modalPrintPendaftaran{{$pendaftaran->id}}">
                                                <i class="fa-solid fa-print"></i>
                                            </button>
                                            @if ($canManageSelectedPeriod)
                                                <button class="btn-action edit" data-toggle="modal"
                                                    data-target="#modalEditPendaftaran{{$pendaftaran->id}}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button class="btn-action delete" data-toggle="modal" data-target="#modalHapusPendaftaran{{$pendaftaran->id}}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
            </section>
        </div>
    </div>

    @push('script')
        <script>
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const jenjangFilter = document.getElementById('jenjangFilter');
            const paymentFilter = document.getElementById('paymentFilter');
            const tableBody = document.getElementById('pendaftaranTableBody');
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

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const jenjangValue = jenjangFilter.value;
                const paymentValue = paymentFilter.value;

                filteredRows = [...allRows].filter(row => {
                    const nameEl = row.querySelector('.student-info h5');
                    const nama = nameEl ? nameEl.innerText.toLowerCase() : '';

                    const matchSearch = nama.includes(searchValue);
                    const matchStatus = statusValue === 'all' || row.dataset.status === statusValue;
                    const matchJenjang = jenjangValue === 'all' || row.dataset.jenjang === jenjangValue;
                    const matchPayment = paymentValue === 'all' || row.dataset.payment === paymentValue;

                    return matchSearch && matchStatus && matchJenjang && matchPayment;
                });

                currentPage = 1;
                renderTable();
            }

            searchInput.addEventListener('keyup', filterTable);
            statusFilter.addEventListener('change', filterTable);
            jenjangFilter.addEventListener('change', filterTable);
            paymentFilter.addEventListener('change', filterTable);

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

            function exportPendaftaran(format) {
                const params = new URLSearchParams({
                    format: format,
                    status: statusFilter.value,
                    jenjang: jenjangFilter.value,
                    payment: paymentFilter.value,
                    cari: searchInput.value,
                });

                window.location.href = "{{ route('pendaftaran.export') }}?" + params.toString();
            }

            document.addEventListener('click', function(e) {
                const menu = document.getElementById('exportMenuPendaftaran');
                if (menu && !e.target.closest('.export-dropdown')) {
                    menu.classList.remove('show');
                }
            });
        </script>
    @endpush
@endsection