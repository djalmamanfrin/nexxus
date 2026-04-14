<?php

use App\Http\Middleware\AttachRequestId;
use App\Http\Middleware\EnableDebugContext;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Support\Logger;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            AttachRequestId::class,
            EnableDebugContext::class,
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (\Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });

        // 🔸 422
        $exceptions->render(function (ValidationException $e) {
            Logger::warning('Validation Exception', $e->errors());
            return back()->withErrors($e->errors());
        });

        $exceptions->render(function (InvalidArgumentException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        });

        $exceptions->render(function (AuthenticationException $e) {
            return redirect()->guest(route('login'));
        });

        $exceptions->render(function (Throwable $e) {
            if (!app()->environment('production')) {
                return null;
            }

            $status = $e instanceof HttpException ? $e->getStatusCode() : 500;

            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }

            return Inertia::render('Error', ['status' => $status]);
        });
    })->create();
