<header class="header-admin">

    <div class="header-left">

        <h2>{{ $title ?? 'Page Title' }}</h2>

    </div>

    <div class="header-right">

        @include('components.notification')

        <button type="button" class="profile-admin" data-toggle="modal" data-target="#editProfileModal">


            <div class="avatar">
                {{ strtoupper(substr(session('admin.nama_lengkap', 'A'), 0, 1)) }}
            </div>

            <div class="text">

                <h4>{{ session('admin.nama_lengkap', 'Admin User') }}</h4>
                <span>{{ ucfirst(session('admin.role', 'administrator')) }}</span>

            </div>
            <i class="fa-solid fa-pen-to-square"></i>
        </button>

    </div>

</header>

<div class="modal fade admin-modal" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <span>Akun Admin</span>
                    <h3>Edit Profile</h3>
                </div>
                <button type="button" class="close-modal" data-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('admin-profile.update') }}" method="POST" id="formUpdateProfile">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', session('admin.nama_lengkap')) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', session('admin.email')) }}" required>
                        </div>
                    </div>
                </form>

                <hr>

                <form action="{{ route('admin-profile.password') }}" method="POST" id="formUpdateOwnPassword">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Password Lama</label>
                            <input type="password" name="current_password" placeholder="Masukkan password lama">
                        </div>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="password" placeholder="Masukkan password baru">
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                <button type="submit" form="formUpdateOwnPassword" class="btn-save">
                    <i class="fa-solid fa-key"></i>
                    Ganti Password
                </button>
                <button type="submit" form="formUpdateProfile" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Profile
                </button>
            </div>
        </div>
    </div>
</div>
