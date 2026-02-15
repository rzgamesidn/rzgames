<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Logic Role abang tetap aman (Jangan diubah)
        $middleware->alias([
            'checkRole' => \App\Http\Middleware\CheckRole::class,
        ]);

        // 2. TAMBAHKAN INI: Pengecualian CSRF buat Midtrans
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback', 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();