<p>Halo {{ $admin->nama_lengkap }},</p>

<p>Berikut adalah link login sementara untuk akun admin PPTU Anda:</p>

<p>
    <a href="{{ $url }}">{{ $url }}</a>
</p>

<p>Link ini hanya berlaku selama {{ $expirationMinutes }} menit, sampai {{ $expiresAt->format('d/m/Y H:i') }}, dan hanya dapat dipakai satu kali.</p>

<p>Setelah masuk, Anda hanya dapat membuka halaman Ganti Password. Silakan segera mengganti password agar akses admin panel terbuka kembali.</p>

<p>Abaikan email ini jika Anda tidak meminta akses login sementara.</p>
