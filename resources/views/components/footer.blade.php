<footer class="footer">
    <div class="container">

        <!-- Kolom 1: Logo & Deskripsi -->
        <div class="footer-col footer-brand">
            <div class="brand-row">
                <img src="{{ asset('assets/pptu.png') }}" alt="Logo PPTU">
                <h2 class="logo">Tarbiyatul 'Ulum</h2>
            </div>
            <p>
                Pondok Pesantren Tarbiyatul 'Ulum Sumursongo berkomitmen menghadirkan pendidikan
                pesantren, formal, dan Al-Qur'an yang hangat, tertib, dan berakhlak.
            </p>
            <div class="footer-badges">
                <span>Pesantren</span>
                <span>Pendidikan Formal</span>
                <span>Al-Qur'an</span>
            </div>
        </div>

        <!-- Kolom 2: Menu -->
        <div class="footer-col">
            <h3>Menu</h3>
            <ul>
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('profile') }}">Profil</a></li>
                <li><a href="{{ route('kegiatan') }}">Kegiatan</a></li>
                <li><a href="{{ route('berita') }}">Berita</a></li>
                <li><a href="{{ route('galeri') }}">Galeri</a></li>
            </ul>
        </div>

        <!-- Kolom 3: Pendidikan -->
        <div class="footer-col">
            <h3>Pendidikan</h3>
            <ul>
                <li><a href="{{ route('pendidikan.tpq') }}">TPQ</a></li>
                <li><a href="{{ route('pendidikan.madqur') }}">Madrasah Al-Qur'an</a></li>
                <li><a href="{{ route('pendidikan.madin') }}">Madrasah Diniyah</a></li>
                <li><a href="{{ route('pendidikan.smp') }}">SMP</a></li>
                <li><a href="{{ route('pendidikan.smk') }}">SMK</a></li>
            </ul>
        </div>

        <!-- Kolom 4: Kontak -->
        <div class="footer-col footer-contact">
            <h3>Kontak</h3>
            <div class="contact-line">
                <i class="fa-solid fa-location-dot"></i>
                <span>Desa Krajan, Sumursongo, Kec. Karas, Kabupaten Magetan, Jawa Timur 63395</span>
            </div>
            <div class="contact-line">
                <i class="fa-solid fa-phone"></i>
                <span>0812-3456-7890</span>
            </div>
            <div class="contact-line">
                <i class="fa-solid fa-envelope"></i>
                <span>pptu@gmail.com</span>
            </div>

            <!-- Sosial Media -->
            <div class="social">
                <a href="#" aria-label="Facebook PPTU"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" aria-label="Instagram PPTU"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" aria-label="YouTube PPTU"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>

    </div>

    <!-- Bottom -->
    <div class="footer-bottom">
        <p>&copy; 2026 PPTU Sumursongo. All rights reserved.</p>
    </div>
</footer>
