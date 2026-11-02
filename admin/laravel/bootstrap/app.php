<?php

use App\Console\AppSchedule;
use App\Http\Middleware\AppMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB
        );
        $middleware->validateCsrfTokens(except: [
            'api/kyc-webhook',
            'api/webhooks/amlbot/monitoring',
        ]);
        app(AppMiddleware::class)($middleware);
    })
    ->withSchedule(function (Schedule $schedule) {
        app(AppSchedule::class)($schedule);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => trans('general.unauthorized'),
                    'status' => false,
                ], 401);
            }

            return redirect()->guest(route('login')); //in web page
        });
    })
    ->create();

