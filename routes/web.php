<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\LandingPage\BeritaController as LandingBeritaController;
use App\Http\Controllers\Admin\GaleriController as AdminGaleriController;
use App\Http\Controllers\LandingPage\GaleriController as LandingGaleriController;
use App\Http\Controllers\Admin\GuruController as AdminGuruController;
use App\Http\Controllers\LandingPage\GuruController as LandingGuruController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\HasilTesController as AdminHasilTesController;
use App\Http\Controllers\Admin\VirtualTourController as AdminVirtualTourController;
use App\Http\Controllers\LandingPage\PembayaranController as LandingPembayaranController;
use App\Http\Controllers\Admin\GelombangPendaftaranController as AdminGelombangController;
use App\Http\Controllers\Admin\JadwalPendaftaranController as AdminJadwalPendaftaranController;
use App\Http\Controllers\Admin\PeriodeController as AdminPeriodeController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;
use App\Http\Controllers\Admin\AdminController as AdminDataController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\LandingPage\PendaftaranController as LandingPendaftaranController;
use App\Http\Controllers\LandingPage\HomeController as LandingHomeController;
use App\Http\Controllers\API\TransaksiController as APITransaksiController;
use App\Http\Controllers\LandingPage\DaftarUlangController as LandingDaftarUlangController;
use App\Http\Controllers\LandingPage\TransaksiController as LandingTransaksiController;

use App\Http\Controllers\LandingPage\PembayaranController;

Route::get('/', [
    LandingHomeController::class,
    'index'
])->name('home');

Route::get('/payment/{id}', [
    APITransaksiController::class,
    'pay'
])->name('payment');

Route::prefix('landing-page')->group(function () {
    Route::get('/profile', function () {
        return view('pages.landing-page.profile', [
            'showModal' => false,
        ]);
    })->name('profile');

    Route::get('/kegiatan', function () {
        return view('pages.landing-page.kegiatan', [
            'showModal' => false,
        ]);
    })->name('kegiatan');

    Route::prefix('berita')->group(function () {
        Route::get('/',
            [LandingBeritaController::class, 'index']
        )->name('berita');

        Route::get('/detail-berita/{slug}',
            [LandingBeritaController::class, 'detailBerita']
        )->name('detail-berita');
    });

    Route::get('/galeri', [LandingGaleriController::class, 'index'])->name('galeri');

    Route::get('/guru', [LandingGuruController::class, 'index'])->name('guru');

    Route::prefix('pendidikan')->group(function () {
        Route::get('/madin', function () {
            return view('pages.landing-page.pendidikan.madin', [
                'showModal' => false,
            ]);
        })->name('pendidikan.madin');

        Route::get('/madqur', function () {
            return view('pages.landing-page.pendidikan.madqur', [
                'showModal' => false,
            ]);
        })->name('pendidikan.madqur');

        Route::get('/tpq', function () {
            return view('pages.landing-page.pendidikan.tpq', [
                'showModal' => false,
            ]);
        })->name('pendidikan.tpq');

        Route::get('/smp', function () {
            return view('pages.landing-page.pendidikan.smp', [
                'showModal' => false,
            ]);
        })->name('pendidikan.smp');

        Route::get('/smk', function () {
            return view('pages.landing-page.pendidikan.smk', [
                'showModal' => false,
            ]);
        })->name('pendidikan.smk');
    });

    Route::get('/virtual-tour', [\App\Http\Controllers\LandingPage\VirtualTourController::class, 'index'])
        ->name('virtual-tour');

    Route::get('/virtual-tour/scene/{scene}', [\App\Http\Controllers\LandingPage\VirtualTourController::class, 'redirectToScene'])
        ->name('virtual-tour.scene.redirect');


    Route::prefix('pendaftaran')->group(function () {
        Route::get('/informasi-pendaftaran',[LandingPembayaranController::class, 'index'])
        ->name('informasi-pendaftaran');

        Route::get('/form',[LandingPendaftaranController::class, 'index']
        )->name('form-pendaftaran');

        Route::post('/store',[LandingPendaftaranController::class, 'store']
        )->name('pendaftaran.store');
        });

    Route::prefix('cek-status')->group(function () {
        Route::get('/', [LandingPendaftaranController::class, 'cekStatus'])
        ->name('cek-pendaftaran');

        Route::post('/cari', [LandingPendaftaranController::class, 'cariStatus'])
        ->name('cek-pendaftaran.cari');

        Route::get('/detail-cek/{pendaftaran}', [LandingPendaftaranController::class, 'detailCek'])
        ->name('detail-cek');
    });

    Route::prefix('daftar-ulang')->group(function () {
        Route::get('/{pendaftaran}', [LandingDaftarUlangController::class, 'show'])
        ->name('daftar-ulang');

        Route::post('/{pendaftaran}/bayar', [LandingDaftarUlangController::class, 'store'])
        ->name('daftar-ulang.bayar');
    });

    Route::prefix('pembayaran')->group(function () {
        Route::get(
            '/pembayaran-pendaftaran/{transaksi}',
            [LandingTransaksiController::class,
            'pembayaranPendaftaran']
        )->name(
            'pembayaran-pendaftaran'
        );

        Route::get(
            '/pembayaran-daftar-ulang/{transaksi}',
            [LandingTransaksiController::class,
            'pembayaranDaftarUlang']
        )->name(
            'pembayaran-daftar-ulang'
        );

        Route::get(
            '/detail-pembayaran/{transaksi}',
            [LandingTransaksiController::class,
            'detailPembayaran']
        )->name(
            'detail-pembayaran'
        );

        Route::get(
            '/pembayaran-berhasil/{transaksi}',
            [LandingTransaksiController::class,
            'pembayaranBerhasil']
        )->name(
            'pembayaran-berhasil'
        );

        Route::get(
            '/download-bukti/{transaksi}',
            [LandingTransaksiController::class,
            'downloadBukti']
        )->name('download-bukti');
        });
    });

Route::prefix('admin')->group(function () {
    Route::get('/login', [
        \App\Http\Controllers\Admin\AuthController::class,
        'showLogin',
    ])->name('admin-login');

    Route::post('/login', [
        \App\Http\Controllers\Admin\AuthController::class,
        'login',
    ])->name('admin-login-post');

    Route::post('/forgot-password', [
        \App\Http\Controllers\Admin\AuthController::class,
        'resetPassword',
    ])->name('admin-password-reset');

    Route::post('/logout', [
        \App\Http\Controllers\Admin\AuthController::class,
        'logout',
    ])->name('admin-logout');

    Route::middleware(['App\\Http\\Middleware\\AdminAuthenticated'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin-dashboard');

        Route::middleware(['App\\Http\\Middleware\\AdminRole:media'])->prefix('media')->group(function () {
            Route::get('/berita', [AdminBeritaController::class, 'index'])
            ->name('admin-berita');

            Route::post('/berita/store', [AdminBeritaController::class, 'store'])
            ->name('berita.store');

            Route::get('/berita/{berita}', [AdminBeritaController::class, 'show'])
            ->name('berita.show');

            Route::get('/galeri', [AdminGaleriController::class, 'index'])
            ->name('admin-galeri');

            Route::post('/galeri/store', [AdminGaleriController::class, 'store'])
            ->name('galeri.store');

            Route::post('/galeri/{galeri}/update', [AdminGaleriController::class, 'update'])
            ->name('galeri.update');

            Route::delete('/galeri/{galeri}', [AdminGaleriController::class, 'destroy'])
            ->name('galeri.destroy');

            Route::post('/berita/{berita}/update', [AdminBeritaController::class, 'update'])
            ->name('berita.update');

            Route::delete('/berita/{berita}', [AdminBeritaController::class, 'destroy'])
            ->name('berita.destroy');

            Route::get('/guru', [AdminGuruController::class, 'index'])
            ->name('admin-guru');

            Route::post('/guru/store', [AdminGuruController::class, 'store'])
            ->name('guru.store');

            Route::post('/guru/{guru}/update', [AdminGuruController::class, 'update'])
            ->name('guru.update');

            Route::delete('/guru/{guru}', [AdminGuruController::class, 'destroy'])
            ->name('guru.destroy');

            Route::get('/virtual-tour', [AdminVirtualTourController::class, 'index'])
            ->name('admin-virtual-tour');

            Route::post('/virtual-tour/scene/store', [AdminVirtualTourController::class, 'storeScene'])
            ->name('virtual-tour.scene.store');

            Route::post('/virtual-tour/scene/{scene}/update', [AdminVirtualTourController::class, 'updateScene'])
            ->name('virtual-tour.scene.update');

            Route::delete('/virtual-tour/scene/{scene}', [AdminVirtualTourController::class, 'destroyScene'])
            ->name('virtual-tour.scene.destroy');

            Route::post('/virtual-tour/scene/{scene}/hotspot/store', [AdminVirtualTourController::class, 'storeHotspot'])
            ->name('virtual-tour.hotspot.store');

            Route::post('/virtual-tour/hotspot/{hotspot}/update', [AdminVirtualTourController::class, 'updateHotspot'])
            ->name('virtual-tour.hotspot.update');

            Route::delete('/virtual-tour/hotspot/{hotspot}', [AdminVirtualTourController::class, 'destroyHotspot'])
            ->name('virtual-tour.hotspot.destroy');
        });

        Route::middleware(['App\\Http\\Middleware\\AdminRole:administrasi'])->prefix('administrasi')->group(function () {
            Route::get('/pendaftaran', [AdminPendaftaranController::class, 'index'])
            ->name('admin-pendaftaran');

            Route::post('/pendaftaran/store', [AdminPendaftaranController::class, 'store'])
            ->name('pendaftaran.store');

            Route::post('/pendaftaran/{pendaftaran}/update', [AdminPendaftaranController::class, 'update'])
            ->name('pendaftaran.update');

            Route::patch('/pendaftaran/{pendaftaran}/status',
            [AdminPendaftaranController::class, 'updateStatus']
            )->name('pendaftaran.update-status');

            Route::get('/pendaftaran/print/{id}',[AdminPendaftaranController::class, 'print'])
            ->name('admin.pendaftaran.print');

            Route::get('/pendaftaran/preview/{id}',[AdminPendaftaranController::class, 'preview'])
            ->name('admin.pendaftaran.preview');

            Route::delete('/pendaftaran/{pendaftaran}', [AdminPendaftaranController::class, 'destroy'])
            ->name('pendaftaran.destroy');

            Route::get('/hasil-tes', [AdminHasilTesController::class, 'index'])
            ->name('admin-hasil-tes');

            Route::post('/hasil-tes/store', [AdminHasilTesController::class, 'store'])
            ->name('hasil-tes.store');

            Route::post('/hasil-tes/{hasilTes}/update', [AdminHasilTesController::class, 'update'])
            ->name('hasil-tes.update');

            Route::delete('/hasil-tes/{hasilTes}', [AdminHasilTesController::class, 'destroy'])
            ->name('hasil-tes.destroy');

            Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])
            ->name('admin-pembayaran');

            Route::post('/pembayaran/store', [AdminPembayaranController::class, 'store'])
            ->name('pembayaran.store');

            Route::post('/pembayaran/{pembayaran}/update', [AdminPembayaranController::class, 'update'])
            ->name('pembayaran.update');

            Route::delete('/pembayaran/{pembayaran}', [AdminPembayaranController::class, 'destroy'])
            ->name('pembayaran.destroy');

            Route::get('/gelombang', [AdminGelombangController::class, 'index'])
            ->name('admin-gelombang');

            Route::post('/gelombang/store', [AdminGelombangController::class, 'store'])
            ->name('gelombang.store');

            Route::post('/gelombang/{gelombang}/update', [AdminGelombangController::class, 'update'])
            ->name('gelombang.update');

            Route::delete('/gelombang/{gelombang}',[AdminGelombangController::class, 'destroy'])
            ->name('gelombang.destroy');

            Route::get('/jadwal-pendaftaran', [AdminJadwalPendaftaranController::class, 'index'])
            ->name('admin-jadwal-pendaftaran');

            Route::post('/jadwal-pendaftaran/store', [AdminJadwalPendaftaranController::class, 'store'])
            ->name('jadwal-pendaftaran.store');

            Route::post('/jadwal-pendaftaran/{jadwal}/update', [AdminJadwalPendaftaranController::class, 'update'])
            ->name('jadwal-pendaftaran.update');

            Route::delete('/jadwal-pendaftaran/{jadwal}', [AdminJadwalPendaftaranController::class, 'destroy'])
            ->name('jadwal-pendaftaran.destroy');

            Route::get('/promo', [AdminPromoController::class, 'index'])
            ->name('admin-promo');

            Route::post('/promo/store', [AdminPromoController::class, 'store'])
            ->name('promo.store');

            Route::post('/promo/{promo}/update', [AdminPromoController::class, 'update'])
            ->name('promo.update');

            Route::delete('/promo/{promo}', [AdminPromoController::class, 'destroy'])
            ->name('promo.destroy');

            Route::get('/periode', [AdminPeriodeController::class, 'index'])
            ->name('admin-periode');

            Route::post('/periode/store', [AdminPeriodeController::class, 'store'])
            ->name('periode.store');

            Route::post('/periode/{periode}/update', [AdminPeriodeController::class, 'update'])
            ->name('periode.update');

            Route::delete('/periode/{periode}', [AdminPeriodeController::class, 'destroy'])
            ->name('periode.destroy');

            Route::get('/data-admin', [AdminDataController::class, 'index'])
            ->name('admin-data');

            Route::post('/data-admin/store', [AdminDataController::class, 'store'])
            ->name('admin-data.store');

            Route::post('/data-admin/{admin}/update', [AdminDataController::class, 'update'])
            ->name('admin-data.update');

            Route::delete('/data-admin/{admin}', [AdminDataController::class, 'destroy'])
            ->name('admin-data.destroy');
        });
    });
});


Route::post(
    '/midtrans/callback',
    [APITransaksiController::class, 'callback']
);

Route::post(
    '/pembayaran/{transaksi}/pilih-metode',
    [LandingTransaksiController::class, 'pilihMetode']
)->name('pilih-metode');
