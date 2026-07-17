<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',

        api: __DIR__.'/../routes/api.php',

        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Pelacak kunjungan membutuhkan session, sehingga hanya berjalan pada grup web.
        $middleware->web(append: [\App\Http\Middleware\TrackWebsiteVisit::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
