@extends('layout.app')

@section('content')
    <div class="admin-layout">
        {{-- SIDEBAR --}}
        @include('components.sidebar-admin')
        <div class="admin-main">
            {{-- HEADER --}}
            @include('components.header-admin', ['title' => 'Pembayaran'])
            <div class="admin-biaya-pendaftaran">
                @include('components.archive-banner')

                {{-- FILTER --}}
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

                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari nama santri..." id="searchInput">
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
                                <option value="belum_dibayar">Belum Dibayar</option>
                                <option value="dicicil">Dicicil</option>
                                <option value="lunas">Lunas</option>
                            </select>
                        </div>
                    </div>
                    <div class="export-dropdown">
                        <button class="btn-add" type="button" onclick="document.getElementById('exportMenuPembayaran').classList.toggle('show')">
                            <i class="fa-solid fa-file-export"></i>
                            Export
                        </button>
                        <div class="export-dropdown-menu" id="exportMenuPembayaran">
                            <a href="#" onclick="exportPembayaran('xlsx'); return false;">
                                <i class="fa-solid fa-file-excel"></i> Excel (.xlsx)
                            </a>
                            <a href="#" onclick="exportPembayaran('pdf'); return false;">
                                <i class="fa-solid fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-card">
                    <div class="table-wrapper">
                        <table id="biayaTable">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Santri</th>
                                    <th>Jenjang</th>
                                    <th>Total Tagihan</th>
                                    <th>Sisa Tagihan</th>
                                    <th>Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="biayaTableBody">
                                @forelse ($tagihans as $tagihan)
                                    <tr data-status="{{ $tagihan->status_pembayaran }}" data-jenjang="{{ $tagihan->jenjang }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="biaya-info">
                                                <h5>{{ $tagihan->pendaftaran->nama_lengkap ?? '-' }}</h5>
                                                <span>NISN: {{ $tagihan->pendaftaran->pendidikan->nisn ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="jenjang-badge {{ strtolower($tagihan->jenjang) }}">
                                                {{ $tagihan->jenjang }}
                                            </div>
                                        </td>
                                        <td class="biaya-nominal">
                                            Rp {{ number_format($tagihan->nominal_akhir, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="payment-badge {{ $tagihan->status_pembayaran === 'lunas' ? 'paid' : 'unpaid' }}">
                                                {{ ucfirst(str_replace('_', ' ', $tagihan->status_pembayaran)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-action">
                                                <button class="btn-action view" data-toggle="modal"
                                                    data-target="#modalCatatBayar{{ $tagihan->id }}" title="Kelola Pembayaran">
                                                    <i class="fa-solid fa-money-bill-wave"></i>
                                                </button>
                                                <a class="btn-action view" href="{{ route('pembayaran-santri.cetak', $tagihan->id) }}" title="Cetak Rincian Tagihan">
                                                    <i class="fa-solid fa-print"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data tagihan.</td>
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
                            Menampilkan 1 - 10 dari {{ $tagihans->count() }} data
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

            </div>
        </div>
    </div>

    {{-- MODAL CATAT BAYAR --}}
    @foreach ($tagihans as $tagihan)
        <div class="modal fade admin-modal" id="modalCatatBayar{{ $tagihan->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title-wrap">
                            <h3>{{ $tagihan->pendaftaran->nama_lengkap ?? '-' }}</h3>
                            <span>Pilih tagihan yang mau dilunasi, lalu catat pembayaran tunai/transfer manual</span>
                        </div>
                        <button type="button" class="close-modal" data-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('pembayaran-santri.catat-bayar', $tagihan->id) }}" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="bill-list">
                                @foreach ($tagihan->details->groupBy('kategori') as $kategori => $details)
                                    <div class="bill-category">
                                        <h5>{{ $kategori }}</h5>
                                    </div>

                                    @foreach ($details as $detail)
                                        @php $isLunas = $detail->status_pembayaran === 'lunas'; @endphp

                                        <label class="bill-item {{ $isLunas ? 'disabled' : '' }}">
                                            <div class="bill-left">
                                                @unless ($isLunas)
                                                    <input type="checkbox" name="detail_ids[]" value="{{ $detail->id }}">
                                                @endunless
                                                <div>
                                                    <h5>{{ $detail->nama_pembayaran }}</h5>
                                                    <p>{{ $detail->kategori }}</p>
                                                </div>
                                            </div>

                                            <div class="bill-right">
                                                <strong>Rp {{ number_format($detail->nominal_akhir, 0, ',', '.') }}</strong>

                                                @if ($isLunas)
                                                    <span class="status lunas">
                                                        <i class="fa-solid fa-circle-check"></i> Lunas
                                                    </span>
                                                @else
                                                    <span class="status pending">
                                                        <i class="fa-regular fa-clock"></i> Belum Bayar
                                                    </span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                @endforeach
                            </div>

                            <div class="form-group" style="margin-top: 16px;">
                                <label>Metode Pembayaran</label>
                                <select name="metode_bayar" required>
                                    <option value="tunai">Tunai</option>
                                    <option value="transfer_manual">Transfer Manual</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Catatan (opsional)</label>
                                <input type="text" name="catatan" placeholder="Contoh: dibayar oleh wali santri langsung ke kantor">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn-save">Catat Pembayaran</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endforeach

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
            const jenjangFilter = document.getElementById('jenjangFilter');
            const statusFilter = document.getElementById('statusFilter');
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
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;
            }

            // FILTER FUNCTION
            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();
                const jenjangValue = jenjangFilter.value;
                const statusValue = statusFilter.value;

                filteredRows = [...allRows].filter(row => {
                    if (!row.querySelector('.biaya-info')) return false;

                    const nama = row.querySelector('.biaya-info').innerText.toLowerCase();
                    const jenjang = row.dataset.jenjang;
                    const status = row.dataset.status;

                    const matchSearch = nama.includes(searchValue);
                    const matchJenjang = jenjangValue === 'all' || jenjang === jenjangValue;
                    const matchStatus = statusValue === 'all' || status === statusValue;

                    return matchSearch && matchJenjang && matchStatus;
                });

                currentPage = 1;
                renderTable();
            }

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

            function exportPembayaran(format) {
                const params = new URLSearchParams({
                    format: format,
                    jenjang: jenjangFilter.value,
                    status: statusFilter.value,
                    cari: searchInput.value,
                });

                window.location.href = "{{ route('pembayaran-santri.export') }}?" + params.toString();
            }

            document.addEventListener('click', function(e) {
                const menu = document.getElementById('exportMenuPembayaran');
                if (menu && !e.target.closest('.export-dropdown')) {
                    menu.classList.remove('show');
                }
            });
        </script>
    @endpush
@endsection