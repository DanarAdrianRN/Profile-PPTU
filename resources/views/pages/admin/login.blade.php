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

                    <form action="{{ route('admin-login-post', [], false) }}" method="POST">

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

                                <input type="text" name="username_or_email" id="loginIdentifier"
                                    value="{{ old('username_or_email') }}" placeholder="Masukkan username atau email admin">

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
                                    <i class="fa-solid fa-eye-slash"></i>
                                </button>

                            </div>

                        </div>

                        <div class="form-extra">

                            <span></span>

                            <a href="#" id="forgotPasswordLink">
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
    <form action="{{ route('admin-password-reset', [], false) }}" method="POST" id="forgotPasswordForm" class="d-none">
        @csrf
        <input type="hidden" name="email" id="forgotPasswordEmail">
    </form>
@endsection

@push('script')
    <script>
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');
        const loginIdentifier = document.getElementById('loginIdentifier');
        const forgotPasswordForm = document.getElementById('forgotPasswordForm');
        const forgotPasswordEmail = document.getElementById('forgotPasswordEmail');

        function showLoginAlert(message) {
            const oldAlert = document.getElementById('forgotPasswordInlineAlert');

            if (oldAlert) {
                oldAlert.remove();
            }

            const alert = document.createElement('div');
            alert.id = 'forgotPasswordInlineAlert';
            alert.className = 'alert alert-danger';
            alert.setAttribute('role', 'alert');
            alert.textContent = message;

            loginIdentifier.closest('.form-group').before(alert);
        }

        forgotPasswordLink.addEventListener('click', function(event) {
            event.preventDefault();

            const email = loginIdentifier.value.trim();
            const isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

            if (!email) {
                showLoginAlert('Masukkan email admin terlebih dahulu pada form login.');
                loginIdentifier.focus();
                return;
            }

            if (!isEmail) {
                showLoginAlert('Gunakan email admin untuk meminta link lupa password.');
                loginIdentifier.focus();
                return;
            }

            forgotPasswordEmail.value = email;
            forgotPasswordForm.submit();
        });

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('fa-eye-slash', !isPassword);
                icon.classList.toggle('fa-eye', isPassword);
            });
        });
    </script>
@endpush
