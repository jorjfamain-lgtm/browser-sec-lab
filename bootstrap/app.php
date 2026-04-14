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
        // اضافه کردن میدل‌ور آسیب‌پذیری به گروه وب
        $middleware->web(append: [
            \App\Http\Middleware\VulnerableHeadersMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'csrf-target', // غیرفعال کردن CSRF برای این روت خاص
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();