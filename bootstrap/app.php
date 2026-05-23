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
        // Đăng ký alias cho route middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // (tùy chọn) thêm global/web/api middleware nếu cần:
        // $middleware->append(\App\Http\Middleware\SomeGlobal::class);
        // $middleware->web(append: [\App\Http\Middleware\SomethingForWeb::class]);
        // $middleware->api(prepend: [\App\Http\Middleware\SomethingForApi::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
