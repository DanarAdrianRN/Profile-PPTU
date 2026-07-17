<header class="header-admin">

    <div class="header-left">

        <h2>{{ $title ?? 'Page Title' }}</h2>

    </div>

    <div class="header-right">

        @include('components.notification')

        <div class="profile-admin">


            <div class="avatar">
                A
            </div>

            <div class="text">

                <h4>{{ session('admin.nama_lengkap', 'Admin User') }}</h4>
                <span>{{ ucfirst(session('admin.role', 'administrator')) }}</span>

            </div>



        </div>

    </div>

</header>
