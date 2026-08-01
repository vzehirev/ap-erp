<?php

use App\Http\Middleware\DemoVisitor;
use App\Http\Middleware\ReadOnlyDemo;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetDemoLocale;
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
        // Behind Coolify's Traefik, so the client address arrives in
        // X-Forwarded-For. This has to be settled before the rate limiter
        // partitions on it, or every visitor shares the proxy's bucket.
        $middleware->trustProxies(at: '*');

        // The gate runs before anything with a side effect: a rejected request
        // never reaches routing, the session or a controller. SecurityHeaders
        // wraps it, so even the 404s and 405s the gate itself produces carry
        // X-Robots-Tag.
        $middleware->prepend([
            SecurityHeaders::class,
            ReadOnlyDemo::class,
        ]);

        // DemoVisitor has to be global rather than part of the `web` group.
        // Laravel sorts Authenticate (via the AuthenticatesRequests contract)
        // ahead of any group middleware it does not know about, so a DemoVisitor
        // registered in the group would run *after* `auth` had already bounced
        // the request to a login page that no longer exists.
        $middleware->append([
            SetDemoLocale::class,
            DemoVisitor::class,
        ]);

        // Rate limiting lives in nginx (limit_req_zone), not here. Laravel's
        // throttle middleware counts in the cache, and every cache store that
        // survives between requests wants somewhere to write - which is exactly
        // what this container does not have.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
