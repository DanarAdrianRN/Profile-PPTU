@extends('layout.app')

@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')
        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Riwayat Transaksi'])
            <div class="admin-biaya-pendaftaran">
                @include('components.archive-banner')

                {{-- TAB NAVIGASI --}}
                <div class="page-tabs">
                    <a href="{{ route('admin-pembayaran-santri') }}"
                        class="{{ Route::is('admin-pembayaran-santri') ? 'active' : '' }}">
                        Tagihan Santri
                    </a>
                    <a href="{{ route('admin-riwayat-transaksi') }}"
                        class="{{ Route::is('admin-riwayat-transaksi') ? 'active' : '' }}">
                        Riwayat Transaksi
                    </a>
                </div>

                {{-- FILTER --}}
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari nama santri..." id="searchInput">
                    </div>
                    <div class="filter-group">
                        <div class="select-wrapper">
                            <select id="sumberFilter">
                                <option value="all">Semua Sumber</option>
                                <option value="midtrans">Midtrans</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>
                        <div class="date-filter">
                            <input type="date" id="dariFilter" title="Dari tanggal">
                            <span>&mdash;</span>
                            <input type="date" id="sampaiFilter" title="Sampai tanggal">
                        </div>
                    </div>
                    <div class="export-dropdown">
                        <button class="btn-add" type="button" onclick="document.getElementById('exportMenuRiwayat').classList.toggle('show')">
                            <i class="fa-solid fa-file-export"></i>
                            Export
                        </button>
                        <div class="export-dropdown-menu" id="exportMenuRiwayat">
                            <a href="#" onclick="exportRiwayat('xlsx'); return false;">
                                <i class="fa-solid fa-file-excel"></i> Excel (.xlsx)
                            </a>
                            <a href="#" onclick="exportRiwayat('pdf'); return false;">
                                <i class="fa-solid fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>

            <div class="table-card">
                <div class="table-wrapper">
                    <table id="riwayatTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Santri</th>
                                <th>Item Dibayar</th>
                                <th>Nominal</th>
                                <th>Sumber</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Dicatat Oleh</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="riwayatTableBody">
                            @forelse ($transaksis as $transaksi)
                                <tr
                                    data-sumber="{{ $transaksi->sumber_pembayaran ?? 'midtrans' }}"
                                    data-status="{{ $transaksi->status }}"
                                    data-tanggal="{{ optional($transaksi->tanggal_bayar)->format('Y-m-d') }}"
                                >
                                    <td>{{ optional($transaksi->tanggal_bayar)->format('d M Y, H:i') ?? '-' }}</td>
                                    <td>
                                        <div class="biaya-info">
                                            <h5>{{ $transaksi->pendaftaran->nama_lengkap ?? '-' }}</h5>
                                            <span>NISN: {{ $transaksi->pendaftaran->pendidikan->nisn ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($transaksi->details->isNotEmpty())
                                            {{ $transaksi->details->map(fn($d) => $d->tagihanSantriDetail->nama_pembayaran ?? '-')->implode(', ') }}
                                        @else
                                            {{ $transaksi->pembayaran->nama_pembayaran ?? '-' }}
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $transaksi->sumber_pembayaran === 'manual' ? 'blue' : 'green' }}">
                                            {{ $transaksi->sumber_pembayaran === 'manual' ? 'Manual' : 'Midtrans' }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $transaksi->payment_type ?? '-')) }}</td>
                                    <td>
                                        <span class="payment-badge {{ $transaksi->status === 'settlement' ? 'paid' : 'unpaid' }}">
                                            {{ ucfirst($transaksi->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaksi->dicatatOlehAdmin->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        @if ($transaksi->status === 'settlement')
                                            <div class="table-action">
                                                <a class="btn-action view" href="{{ route('download-bukti', $transaksi->id) }}" target="_blank" title="Cetak Struk">
                                                    <i class="fa-solid fa-receipt"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center">Belum ada transaksi.</td></tr>
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
                    <div class="table-info" id="tableInfo">Menampilkan data</div>
                    <div class="pagination-wrapper">
                        <button class="pagination-btn" id="prevPage"><i class="fa-solid fa-chevron-left"></i></button>
                        <div class="pagination-number" id="paginationNumber">1</div>
                        <button class="pagination-btn" id="nextPage"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            const searchInput = document.getElementById('searchInput');
            const sumberFilter = document.getElementById('sumberFilter');
            const dariFilter = document.getElementById('dariFilter');
            const sampaiFilter = document.getElementById('sampaiFilter');
            const tableBody = document.getElementById('riwayatTableBody');
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
                const sumberValue = sumberFilter.value;
                const dari = dariFilter.value;
                const sampai = sampaiFilter.value;

                filteredRows = [...allRows].filter(row => {
                    const nameEl = row.querySelector('.biaya-info h5');
                    const nama = nameEl ? nameEl.innerText.toLowerCase() : '';
                    const tanggal = row.dataset.tanggal;

                    const matchSearch = nama.includes(searchValue);
                    const matchSumber = sumberValue === 'all' || row.dataset.sumber === sumberValue;
                    const matchDari = !dari || (tanggal && tanggal >= dari);
                    const matchSampai = !sampai || (tanggal && tanggal <= sampai);

                    return matchSearch && matchSumber && matchDari && matchSampai;
                });

                currentPage = 1;
                renderTable();
            }

            searchInput.addEventListener('keyup', filterTable);
            sumberFilter.addEventListener('change', filterTable);
            dariFilter.addEventListener('change', filterTable);
            sampaiFilter.addEventListener('change', filterTable);

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

            function exportRiwayat(format) {
                const params = new URLSearchParams({
                    format: format,
                    sumber: sumberFilter.value,
                    cari: searchInput.value,
                    dari: dariFilter.value,
                    sampai: sampaiFilter.value,
                });

                window.location.href = "{{ route('riwayat-transaksi.export') }}?" + params.toString();
            }

            document.addEventListener('click', function(e) {
                const menu = document.getElementById('exportMenuRiwayat');
                if (menu && !e.target.closest('.export-dropdown')) {
                    menu.classList.remove('show');
                }
            });

            renderTable();
        </script>
    @endpush
@endsection