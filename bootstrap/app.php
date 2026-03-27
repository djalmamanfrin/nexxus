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
            Logger::error('Exception captured', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });

        // 🔸 422
        $exceptions->render(function (ValidationException $e) {
            return back()->withErrors($e->errors())->with('flash', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        });

        // 🔸 HTTP (404, 403...)
        $exceptions->render(function (HttpException $e) {
            $message = match ($e->getStatusCode()) {
                403 => 'Você não tem permissão para isso.',
                404 => 'Recurso não encontrado.',
                default => 'Erro na requisição.',
            };

            return back()->with('flash', [
                'type' => 'error',
                'message' => $message
            ]);
        });

        $exceptions->render(function (AuthenticationException $e) {
            return redirect()->guest(route('login'));
        });

        $exceptions->render(function () {
            return back()->with('flash', [
                'type' => 'error',
                'message' => 'Erro interno. Tente novamente.'
            ]);
        });
    })->create();
