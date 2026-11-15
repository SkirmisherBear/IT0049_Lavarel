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
        'role' => \App\Http\Middleware\EnsureUserRole::class,
    ]);

    $middleware->redirectUsersTo(function ($request) {
    return match ($request->user()?->role) {
        'admin'  => route('pos.dashboard'),
        'client' => route('member.portal'),
        default  => route('pos.dashboard'),
    };
    });
})


    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function ($response) {
    if ($response->getStatusCode() === 419) {
        return back()->withErrors([
            'email' => 'Your session expired while idle. Please sign in again.',
        ]);
    }

    return $response;
});
    })->create();
    
    