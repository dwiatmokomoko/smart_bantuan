<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\EnsurePreEligible;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => Authenticate::class,
            'pre.eligible' => EnsurePreEligible::class,
        ])
        ->redirectGuestsTo(fn () => route("admin.login"))
        ->redirectUsersTo(fn () => route("admin.home"));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

