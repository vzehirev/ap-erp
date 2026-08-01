<?php

namespace App\Http\Middleware;

use App\Demo\DemoNotice;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Layer 2 and 3 of the read-only guarantee: a method check and a route
 * allow-list, both applied before routing.
 *
 * Every write in the original application sits behind POST, so refusing
 * anything that is not GET or HEAD disables all of them at once - including any
 * that might be added later. The allow-list then covers the reverse case: a
 * readable address that simply is not part of the demo.
 *
 * This is deliberately a list of literal paths rather than a pattern. There are
 * fourteen public pages and no id-bearing routes, so an exact set is both
 * complete and impossible to widen by accident.
 */
class ReadOnlyDemo
{
    private const READ_METHODS = ['GET', 'HEAD', 'OPTIONS'];

    private const ALLOWED_PATHS = [
        '/',
        '/bought-materials',
        '/sorted-materials',
        '/ground-materials',
        '/washed-materials',
        '/granular-materials',
        '/sold-materials',
        '/expenses',
        '/salaries',
        '/prepaid',
        '/available-materials',
        '/reports',
        '/others',
        '/privacy',
        '/up',
    ];

    /**
     * Served by nginx in the container and never reaching PHP, but listed so the
     * demo behaves identically under `artisan serve`.
     */
    private const ALLOWED_PREFIXES = ['/css/', '/js/', '/img/', '/vendor/'];

    private const ALLOWED_FILES = ['/favicon.svg', '/robots.txt'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = SetDemoLocale::resolve($request);

        if (! in_array($request->getMethod(), self::READ_METHODS, true)) {
            return DemoNotice::response(DemoNotice::READ_ONLY, 405, $locale);
        }

        if (! $this->isAllowed($request->getPathInfo())) {
            return DemoNotice::response(DemoNotice::NOT_PUBLISHED, 404, $locale);
        }

        return $next($request);
    }

    private function isAllowed(string $path): bool
    {
        $path = rtrim($path, '/');

        if ($path === '') {
            $path = '/';
        }

        $lower = mb_strtolower($path);

        if (in_array($lower, self::ALLOWED_PATHS, true) || in_array($lower, self::ALLOWED_FILES, true)) {
            return true;
        }

        foreach (self::ALLOWED_PREFIXES as $prefix) {
            if (str_starts_with($lower, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
