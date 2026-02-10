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
        $middleware->alias([
            'IdentifyTenant' => \App\Http\Middleware\IdentifyTenant::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle API exceptions to avoid exposing technical details
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                // Return user-friendly error messages for API requests
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'success' => false,
                        'failed' => true,
                        'message' => __('messages.api.validation_failed'),
                        'data' => null,
                        'errors' => $e->errors(),
                    ], 422);
                }

                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'success' => false,
                        'failed' => true,
                        'message' => __('messages.api.unauthorized'),
                        'data' => null,
                        'errors' => [],
                        'error_code' => 'UNAUTHORIZED',
                    ], 401);
                }

                // For other exceptions, return generic error message
                // Only show detailed errors in development
                $message = config('app.debug') 
                    ? $e->getMessage() 
                    : __('messages.api.server_error');

                return response()->json([
                    'success' => false,
                    'failed' => true,
                    'message' => $message,
                    'data' => null,
                    'errors' => config('app.debug') ? ['exception' => get_class($e)] : [],
                ], 500);
            }
        });
    })->create();
