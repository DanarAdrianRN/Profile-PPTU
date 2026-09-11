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
        <li><a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')]) @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a></li>
        <li><a href="{{ route('profile') }}" @class(['active' => request()->routeIs('profile')]) @if(request()->routeIs('profile')) aria-current="page" @endif>Profile</a></li>
        <li><a href="{{ route('kegiatan') }}" @class(['active' => request()->routeIs('kegiatan')]) @if(request()->routeIs('kegiatan')) aria-current="page" @endif>Kegiatan</a></li>
        <li><a href="{{ route('berita') }}" @class(['active' => request()->routeIs('berita', 'detail-berita')]) @if(request()->routeIs('berita', 'detail-berita')) aria-current="page" @endif>Berita</a></li>
        <li><a href="{{ route('galeri') }}" @class(['active' => request()->routeIs('galeri')]) @if(request()->routeIs('galeri')) aria-current="page" @endif>Galeri</a></li>
        <li class="dropdown">
            <button type="button" @class(['dropdown-togglle', 'active' => request()->routeIs('pendidikan.*', 'guru')]) aria-expanded="false" aria-controls="education-menu">
                Pendidikan
                <i class="fa-solid fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu" id="education-menu">
                <li><a href="{{ route('pendidikan.tpq') }}" @class(['active' => request()->routeIs('pendidikan.tpq')])>Taman Pendidikan Al-Qur'an</a></li>
                <li><a href="{{ route('pendidikan.madqur') }}" @class(['active' => request()->routeIs('pendidikan.madqur')])>Madrasah Al-Qur'an</a></li>
                <li><a href="{{ route('pendidikan.madin') }}" @class(['active' => request()->routeIs('pendidikan.madin')])>Madrasah Diniyah</a></li>
                <li><a href="{{ route('pendidikan.smp') }}" @class(['active' => request()->routeIs('pendidikan.smp')])>SMP</a></li>
                <li><a href="{{ route('pendidikan.smk') }}" @class(['active' => request()->routeIs('pendidikan.smk')])>SMK</a></li>
                <li><a href="{{ route('guru') }}" @class(['active' => request()->routeIs('guru')])>Guru</a></li>
            </ul>
        </li>
        <li><a href="{{ route('virtual-tour') }}" @class(['active' => request()->routeIs('virtual-tour', 'virtual-tour.scene.redirect')]) @if(request()->routeIs('virtual-tour', 'virtual-tour.scene.redirect')) aria-current="page" @endif>Virtual Tour</a></li>
        <li><a href="{{ route('informasi-pendaftaran') }}" @class(['btn', 'active' => request()->routeIs('informasi-pendaftaran', 'form-pendaftaran')]) @if(request()->routeIs('informasi-pendaftaran', 'form-pendaftaran')) aria-current="page" @endif>PPDB</a></li>
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
