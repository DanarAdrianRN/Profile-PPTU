@extends('layout.app')

@section('content')
    <section class="login-page">

        <div class="login-wrapper">

            {{-- LEFT --}}
            <div class="login-banner">

                <div class="overlay"></div>
                <div class="banner-content">

                    <span class="badge">
                        Admin Panel
                    </span>

                    <h1>
                        Yayasan Tarbiyatul Ulum
                    </h1>

                    <p>
                        Sistem administrasi dan pengelolaan website yayasan,
                        pendidikan, berita, galeri, dan pendaftaran santri.
                    </p>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="login-form-area">

                <div class="login-card">

                    <div class="logo">
                        <img src="{{ asset('assets/pptu.png') }}" alt="">
                    </div>

                    <div class="title">

                        <h2>
                            Selamat Datang
                        </h2>

                        <p>
                            Silakan login untuk masuk ke dashboard admin
                        </p>

                    </div>

                    <form action="{{ route('admin-login-post') }}" method="POST">

                        @csrf

                        @if (session('login_error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('login_error') }}
                            </div>
                        @endif

                        @if (session('forgot_success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('forgot_success') }}
                            </div>
                        @endif

                        @if (session('forgot_error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('forgot_error') }}
                            </div>
                        @endif

                        @error('username_or_email')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        @error('password')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        @error('role')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-group">

                            <label>Username / Email</label>

                            <div class="input-group-custom">

                                <i class="fa-solid fa-user"></i>

                                <input type="text" name="username_or_email" value="{{ old('username_or_email') }}"
                                    placeholder="Masukkan username atau email admin">

                            </div>

                        </div>

                        <div class="form-group">

                            <label>Role</label>

                            <div class="input-group-custom">

                                <i class="fa-solid fa-user-shield"></i>

                                <select name="role" required>
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                                        Pilih role admin
                                    </option>
                                    <option value="administrasi" {{ old('role') === 'administrasi' ? 'selected' : '' }}>
                                        Administrasi
                                    </option>
                                    <option value="media" {{ old('role') === 'media' ? 'selected' : '' }}>
                                        Media
                                    </option>
                                </select>

                            </div>

                        </div>

                        <div class="form-group">

                            <label>Password</label>

                            <div class="input-group-custom">

                                <i class="fa-solid fa-lock"></i>

                                <input type="password" name="password" id="loginPassword" placeholder="Masukkan password">

                                <button type="button" class="toggle-password" data-target="loginPassword"
                                    aria-label="Tampilkan password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                            </div>

                        </div>

                        <div class="form-extra">

                            <label class="remember">

                                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>

                                <span>Ingat saya</span>

                            </label>

                            <a href="#" data-toggle="modal" data-target="#forgotPasswordModal">
                                Lupa Password?
                            </a>

                        </div>

                        <button type="submit" class="btn-login">

                            <i class="fa-solid fa-right-to-bracket"></i>

                            Masuk Dashboard

                        </button>

                    </form>


                </div>

            </div>

        </div>

    </section>

    <div class="modal fade admin-modal" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('admin-password-reset') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <div class="modal-title-wrap">
                        <span>Akses Admin</span>
                        <h3>Lupa Password</h3>
                    </div>
                    <button type="button" class="close-modal" data-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Username / Email</label>
                            <input type="text" name="username_or_email" value="{{ old('username_or_email') }}"
                                placeholder="Masukkan username atau email admin" required>
                        </div>

                        <div class="form-group full">
                            <label>Role</label>
                            <select name="role" required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                                    Pilih role admin
                                </option>
                                <option value="administrasi" {{ old('role') === 'administrasi' ? 'selected' : '' }}>
                                    Administrasi
                                </option>
                                <option value="media" {{ old('role') === 'media' ? 'selected' : '' }}>
                                    Media
                                </option>
                            </select>
                        </div>

                        <div class="form-group full">
                            <label>Password Baru</label>
                            <div class="input-group-custom">
                                <input type="password" name="password" id="newPassword"
                                    placeholder="Masukkan password baru" required>
                                <button type="button" class="toggle-password" data-target="newPassword"
                                    aria-label="Tampilkan password baru">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group full">
                            <label>Konfirmasi Password</label>
                            <div class="input-group-custom">
                                <input type="password" name="password_confirmation" id="confirmPassword"
                                    placeholder="Ulangi password baru" required>
                                <button type="button" class="toggle-password" data-target="confirmPassword"
                                    aria-label="Tampilkan konfirmasi password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-key"></i>
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            });
        });
    </script>
@endpush
