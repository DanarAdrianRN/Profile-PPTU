<div class="admin-notifications">

    <div class="notification-dropdown" id="adminNotificationDropdown">

        <!-- BUTTON -->
        <button class="notification-btn"
            type="button"
            aria-label="Notifikasi"
            aria-expanded="false">

            <i class="fa-regular fa-bell"></i>

            @if ($adminNotificationCount > 0)
                <span class="notification-badge">
                    {{ $adminNotificationCount > 99 ? '99+' : $adminNotificationCount }}
                </span>
            @endif

        </button>

        <!-- MENU -->
        <div class="notification-menu">

            <!-- HEADER -->
            <div class="notification-header">

                <div>
                    <h4>Notifikasi</h4>
                    <p>Aktivitas terbaru sistem</p>
                </div>

                <span class="notification-count">
                    {{ $adminNotificationCount }} Baru
                </span>

            </div>

            <!-- LIST -->
            <div class="notification-list">

                @forelse ($adminNotifications as $notification)
                    <a href="{{ $notification['url'] }}" class="notification-item">

                        <div class="notification-icon {{ $notification['color'] }}">
                            <i class="{{ $notification['icon'] }}"></i>
                        </div>

                        <div class="notification-content">

                            <div class="notification-top">
                                <h5>{{ $notification['title'] }}</h5>
                                <span>{{ $notification['time'] }}</span>
                            </div>

                            <p>{{ $notification['message'] }}</p>

                        </div>

                    </a>
                @empty
                    <div class="notification-item">
                        <div class="notification-icon green">
                            <i class="fa-regular fa-circle-check"></i>
                        </div>

                        <div class="notification-content">
                            <div class="notification-top">
                                <h5>Belum Ada Notifikasi</h5>
                            </div>

                            <p>Aktivitas terbaru akan muncul di sini.</p>
                        </div>
                    </div>
                @endforelse

            </div>

        </div>

    </div>

</div>

<script>
    (function() {

        const dropdown =
            document.getElementById(
                'adminNotificationDropdown'
            );

        if (!dropdown) return;

        const btn =
            dropdown.querySelector(
                '.notification-btn'
            );

        const menu =
            dropdown.querySelector(
                '.notification-menu'
            );

        btn.addEventListener(
            'click',
            function(e) {

                e.stopPropagation();

                dropdown.classList.toggle('open');
                btn.setAttribute(
                    'aria-expanded',
                    dropdown.classList.contains('open')
                );

            }
        );

        document.addEventListener(
            'click',
            function() {

                dropdown.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');

            }
        );

        menu.addEventListener(
            'click',
            function(e) {

                e.stopPropagation();

            }
        );

    })();
</script>
