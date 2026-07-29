@extends('layout.app')

@section('content')
<div class="admin-modal" style="margin: 0 auto; max-width: 820px;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <span>Login Sementara</span>
                    <h3>Ganti Password</h3>
                </div>
            </div>

            <form action="{{ route('admin-password-force-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">

                        <div class="form-group full">
                            <label>Password Baru</label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password"
                                    placeholder="Masukkan password baru" required>
                                <i class="fa-solid fa-eye-slash toggle-password"
                                   data-target="password"></i>
                            </div>
                        </div>

                        <div class="form-group full">
                            <label>Konfirmasi Password Baru</label>
                            <div class="password-wrapper">
                                <input type="password" id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Ulangi password baru" required>
                                <i class="fa-solid fa-eye-slash toggle-password"
                                   data-target="password_confirmation"></i>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-key"></i>
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>

</style>

<script>
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function () {
        const input = document.getElementById(this.dataset.target);

        if (input.type === 'password') {
            input.type = 'text';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        } else {
            input.type = 'password';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        }
    });
});
</script>
@endsection