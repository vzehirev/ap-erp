<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * The original is Bulgarian-only. The demo carries an English translation
 * alongside it, chosen with `?lang=en`.
 *
 * The choice lives in the query string rather than a cookie or the session so
 * that a link to a page in English stays in English when it is shared, and so
 * the demo keeps working identically for a visitor who blocks cookies. Internal
 * links carry it forward through the durl() helper.
 */
class SetDemoLocale
{
    public const DEFAULT = 'bg';

    public const SUPPORTED = ['bg', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale(self::resolve($request));

        return $next($request);
    }

    /**
     * Usable before the middleware stack has run, so the gate can render its
     * notices in the language the visitor asked for.
     */
    public static function resolve(Request $request): string
    {
        $requested = $request->query('lang');

        if (is_string($requested) && in_array($requested, self::SUPPORTED, true)) {
            return $requested;
        }

        return self::DEFAULT;
    }
}
