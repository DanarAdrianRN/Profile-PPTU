<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TransaksiController;

Route::get(
    '/payment/{id}',
    [TransaksiController::class, 'payment']
);

Route::get(
    '/payment-status/{id}',
    [TransaksiController::class, 'status']
);

Route::post(
    '/payment-confirm/{id}',
    [TransaksiController::class, 'confirm']
);
