<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            \App\Http\Middleware\AutoLogoutInactiveUser::class,
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->wantsJson() || $request->ajax() || $request->is('otp/*') || $request->is('login') || $request->is('register') || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'CSRF token renewed. Please try again.',
                    'csrf_token' => csrf_token(),
                ], 419);
            }
            return redirect()->route('login')->with('error', 'Your session expired. Please sign in again.');
        });
    })->create();
