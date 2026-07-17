@php
    $alerts = [
        'success' => ['title' => 'Berhasil', 'icon' => 'fa-circle-check'],
        'error' => ['title' => 'Terjadi Kesalahan', 'icon' => 'fa-circle-xmark'],
        'warning' => ['title' => 'Perlu Perhatian', 'icon' => 'fa-triangle-exclamation'],
        'info' => ['title' => 'Informasi', 'icon' => 'fa-circle-info'],
        'login_error' => ['title' => 'Login Tidak Berhasil', 'icon' => 'fa-circle-xmark'],
        'forgot_error' => ['title' => 'Permintaan Tidak Dapat Diproses', 'icon' => 'fa-circle-xmark'],
        'forgot_success' => ['title' => 'Password Diperbarui', 'icon' => 'fa-circle-check'],
    ];
@endphp

<div class="flash-alerts" aria-live="polite" aria-atomic="true">
    @foreach ($alerts as $key => $meta)
        @if (session()->has($key))
            @php($type = in_array($key, ['login_error', 'forgot_error']) ? 'error' : ($key === 'forgot_success' ? 'success' : $key))
            <div class="flash-alert flash-alert--{{ $type }}" role="alert">
                <i class="fa-solid {{ $meta['icon'] }}" aria-hidden="true"></i>
                <div><strong>{{ $meta['title'] }}</strong><p>{{ session($key) }}</p></div>
                <button type="button" class="flash-alert__close" aria-label="Tutup notifikasi">&times;</button>
            </div>
        @endif
    @endforeach

    @if ($errors->any())
        <div class="flash-alert flash-alert--warning" role="alert">
            <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
            <div><strong>Data Belum Lengkap</strong><p>{{ $errors->first() }}</p></div>
            <button type="button" class="flash-alert__close" aria-label="Tutup notifikasi">&times;</button>
        </div>
    @endif
</div>
