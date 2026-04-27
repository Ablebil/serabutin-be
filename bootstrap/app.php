<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/health',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\RequestId::class,
            \App\Http\Middleware\RequestLogger::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {

                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('general.unauthenticated'),
                    ], 401);
                }

                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('general.forbidden'),
                    ], 403);
                }

                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('general.not_found'),
                    ], 404);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('general.not_found'),
                    ], 404);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('general.method_not_allowed'),
                    ], 405);
                }

                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('general.validation_error'),
                        'errors' => $e->errors(),
                    ], 422);
                }

                if ($e instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('general.payload_too_large'),
                    ], 413);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => __('general.server_error'),
                ], 500);
            }
        });
    })->create();
