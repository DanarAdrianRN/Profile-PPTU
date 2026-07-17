<?php

namespace App\Providers;

use App\Models\Pendaftaran;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.notification', function ($view) {
            $notifications = collect();
            $notificationCount = 0;

            if (Schema::hasTable('pendaftarans')) {
                $pendaftarans = Pendaftaran::latest()
                    ->take(4)
                    ->get()
                    ->map(fn (Pendaftaran $pendaftaran) => [
                        'title' => 'Pendaftaran Baru',
                        'message' => $pendaftaran->nama_lengkap . ' telah melakukan pendaftaran santri baru.',
                        'time' => $pendaftaran->created_at?->diffForHumans(null, true),
                        'created_at' => $pendaftaran->created_at,
                        'icon' => 'fa-solid fa-user-plus',
                        'color' => 'blue',
                        'url' => route('admin-pendaftaran'),
                    ]);

                $notifications = $notifications->merge($pendaftarans);
                $notificationCount += Pendaftaran::whereDate('created_at', today())->count();
            }

            if (Schema::hasTable('transaksis')) {
                $transaksis = Transaksi::with('pendaftaran')
                    ->where('status', 'settlement')
                    ->latest()
                    ->take(4)
                    ->get()
                    ->map(fn (Transaksi $transaksi) => [
                        'title' => 'Pembayaran Berhasil',
                        'message' => 'Pembayaran atas nama ' .
                            ($transaksi->pendaftaran?->nama_lengkap ?? $transaksi->kode_transaksi) .
                            ' telah berhasil.',
                        'time' => ($transaksi->tanggal_bayar ?? $transaksi->updated_at)?->diffForHumans(null, true),
                        'created_at' => $transaksi->tanggal_bayar ?? $transaksi->updated_at,
                        'icon' => 'fa-solid fa-wallet',
                        'color' => 'yellow',
                        'url' => route('admin-pembayaran'),
                    ]);

                $notifications = $notifications->merge($transaksis);
                $notificationCount += Transaksi::where('status', 'settlement')
                    ->whereDate('updated_at', today())
                    ->count();
            }

            $view->with([
                'adminNotifications' => $notifications
                    ->sortByDesc('created_at')
                    ->take(5)
                    ->values(),
                'adminNotificationCount' => $notificationCount,
            ]);
        });
    }
}
