<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\SecurityMonitor;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases
        |--------------------------------------------------------------------------
        */

        $middleware->alias([
            'role' => CheckRole::class,
            'maintenance' => CheckMaintenanceMode::class,
            'security.monitor' => SecurityMonitor::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Web Middleware
        |--------------------------------------------------------------------------
        |
        | CheckMaintenanceMode:
        | Checks whether the system is currently under maintenance.
        |
        | SecurityMonitor:
        | Records suspicious request indicators such as:
        | - SQL injection patterns
        | - XSS patterns
        | - path traversal attempts
        | - attempts to access .env
        | - common web-shell patterns
        | - suspicious scanner paths
        |
        */

        $middleware->web(append: [
            CheckMaintenanceMode::class,
            SecurityMonitor::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();