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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'firebase.role' => \App\Http\Middleware\EnsureFirebaseRole::class,
        ]);

        $trusted = (string) env('TRUSTED_PROXIES', '');
        if ($trusted !== '') {
            $at = $trusted === '*'
                ? '*'
                : array_values(array_filter(array_map('trim', explode(',', $trusted))));
            $middleware->trustProxies(at: $at);
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
