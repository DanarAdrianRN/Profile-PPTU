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
                                    </td>
                                    <td>
                                        <div class="table-action">
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
            const tableRows = document.querySelectorAll('#periodeTableBody tr');

            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();

                tableRows.forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(searchValue)
                        ? ''
                        : 'none';
                });
            });
        </script>
    @endpush
@endsection
