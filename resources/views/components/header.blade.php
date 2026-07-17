<nav class="navbar-container">
    <!-- Logo -->
    <div class="logo">
        <img src="{{ asset('assets/pptu.png') }}" alt="Logo pptu">
        <a href="{{ route('home') }}" class="name">Tarbiyatul 'Ulum</a>
    </div>

    <input type="checkbox" id="nav-toggle" class="nav-toggle">
    <label for="nav-toggle" class="burger-menu" aria-label="Buka menu navigasi">
        <span></span>
        <span></span>
        <span></span>
    </label>

    <ul class="menu">
        <li><a href="{{ route('home') }}">Beranda</a></li>
        <li><a href="{{ route('profile') }}">Profile</a></li>
        <li><a href="{{ route('kegiatan') }}">Kegiatan</a></li>
        <li><a href="{{ route('berita') }}">Berita</a></li>
        <li><a href="{{ route('galeri') }}">Galeri</a></li>
        <li class="dropdown">
            <button type="button" class="dropdown-togglle" aria-expanded="false" aria-controls="education-menu">
                Pendidikan
                <i class="fa-solid fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu" id="education-menu">
                <li><a href="{{ route('pendidikan.tpq') }}">Taman Pendidikan Al-Qur'an</a></li>
                <li><a href="{{ route('pendidikan.madqur') }}">Madrasah Al-Qur'an</a></li>
                <li><a href="{{ route('pendidikan.madin') }}">Madrasah Diniyah</a></li>
                <li><a href="{{ route('pendidikan.smp') }}">SMP</a></li>
                <li><a href="{{ route('pendidikan.smk') }}">SMK</a></li>
                <li><a href="{{ route('guru') }}">Guru</a></li>
            </ul>
        </li>
        <li><a href="{{ route('virtual-tour') }}">Virtual Tour</a></li>
        <li><a href="{{ route('informasi-pendaftaran') }}" class="btn">Daftar</a></li>
    </ul>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropdown = document.querySelector('.navbar-container .dropdown');
        const toggle = dropdown?.querySelector('.dropdown-togglle');

        if (!dropdown || !toggle) return;

        toggle.addEventListener('click', () => {
            const isOpen = dropdown.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', String(isOpen));
        });
    });
</script>
