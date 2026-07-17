@extends('layout.app')

@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')

        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Data Admin'])

            <section class="guru-admin">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

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
                            <tbody>
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
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();

            document.querySelectorAll('#adminTable tbody tr').forEach(row => {
                const text = [...row.querySelectorAll('.admin-search')]
                    .map(cell => cell.innerText.toLowerCase())
                    .join(' ');

                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    </script>
@endpush
