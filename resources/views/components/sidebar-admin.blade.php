<aside class="sidebar-admin">
    @php
        $adminRole = session('admin.role');
    @endphp

    <div class="sidebar-top">

        <a href="#" class="logo">
            <h2>Tarbiyatul</h2>
            <span>Ulum Foundation</span>
        </a>

    </div>

    <div class="group-menu">

        <div class="sidebar-menu">

            <a href="{{ route('admin-dashboard') }}" class="menu-item {{ Route::is('admin-dashboard') ? 'active' : '' }}">

                <i class="fa-solid fa-table-columns"></i>
                <span>Dashboard</span>

            </a>



            @if ($adminRole === 'media')
                <a href="{{ route('admin-berita') }}" class="menu-item {{ Route::is('admin-berita') ? 'active' : '' }}">

                    <i class="fa-regular fa-newspaper"></i>
                    <span>Berita</span>

                </a>



                <a href="{{ route('admin-galeri') }}" class="menu-item {{ Route::is('admin-galeri') ? 'active' : '' }}">

                    <i class="fa-regular fa-image"></i>
                    <span>Galeri</span>

                </a>



                <a href="{{ route('admin-guru') }}" class="menu-item {{ Route::is('admin-guru') ? 'active' : '' }}">

                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Guru</span>

                </a>



                <a href="{{ route('admin-virtual-tour') }}"
                    class="menu-item {{ Route::is('admin-virtual-tour') ? 'active' : '' }}">

                    <i class="fa-regular fa-compass"></i>
                    <span>Virtual Tour</span>

                </a>
            @endif



            @if ($adminRole === 'administrasi')
                <a href="{{ route('admin-pendaftaran') }}"
                    class="menu-item {{ Route::is('admin-pendaftaran') ? 'active' : '' }}">

                    <i class="fa-regular fa-clipboard"></i>
                    <span>Pendaftaran</span>

                </a>

                <a href="{{ route('admin-hasil-tes')}}" class="menu-item {{ Route::is('admin-hasil-tes') ? 'active' : '' }}">

                    <i class="fa-solid fa-file-pen"></i>
                    <span>Hasil Tes</span>

                </a>

                {{-- DROPDOWN --}}
                <div class="menu-dropdown {{ Route::is('admin-gelombang', 'admin-jadwal-pendaftaran', 'admin-pembayaran', 'admin-promo', 'admin-periode') ? 'open' : '' }}">

                    {{-- MAIN MENU --}}
                    <button class="menu-item dropdown-toggle">

                        <div class="menu-left">

                            <i class="fa-regular fa-file-lines"></i>
                            <span>Informasi Pendaftaran</span>

                        </div>

                        <i class="fa-solid fa-chevron-down arrow"></i>

                    </button>



                    {{-- SUB MENU --}}
                    <div class="submenu">

                        <a href="{{ route('admin-gelombang') }}"
                            class="submenu-item {{ Route::is('admin-gelombang') ? 'active' : '' }}">

                            <span class="line"></span>

                            <span>Gelombang</span>

                        </a>

                        <a href="{{ route('admin-jadwal-pendaftaran') }}"
                            class="submenu-item {{ Route::is('admin-jadwal-pendaftaran') ? 'active' : '' }}">

                            <span class="line"></span>

                            <span>Jadwal</span>

                        </a>



                        <a href="{{ route('admin-pembayaran') }}"
                            class="submenu-item {{ Route::is('admin-pembayaran') ? 'active' : '' }}">

                            <span class="line"></span>

                            <span>Pembayaran</span>

                        </a>

                        <a href="{{ route('admin-promo') }}"
                            class="submenu-item {{ Route::is('admin-promo') ? 'active' : '' }}">

                            <span class="line"></span>

                            <span>Promo</span>

                        </a>



                        <a href="{{ route('admin-periode') }}"
                            class="submenu-item {{ Route::is('admin-periode') ? 'active' : '' }}">

                            <span class="line"></span>

                            <span>Periode</span>

                        </a>

                    </div>

                </div>

                <a href="{{ route('admin-data') }}" class="menu-item {{ Route::is('admin-data') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>Data Admin</span>
                </a>
            @endif

        </div>



        <div class="sidebar-bottom">

            <form method="POST" action="{{ route('admin-logout') }}" class="d-inline">
                @csrf

                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </form>

        </div>


    </div>

</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const dropdownToggle =
            document.querySelector('.dropdown-toggle');

        const menuDropdown =
            document.querySelector('.menu-dropdown');

        if (!dropdownToggle || !menuDropdown) {
            return;
        }

        dropdownToggle.addEventListener('click', function() {

            menuDropdown.classList.toggle('open');

        });

    });
</script>
