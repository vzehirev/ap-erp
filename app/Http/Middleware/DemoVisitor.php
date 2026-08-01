<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Layer 1: the demo has no sign-in at all.
 *
 * Every request arrives already authenticated as the single seeded user, so a
 * visitor lands straight in the application. This is not a shortcut around the
 * original's security - it removes it from the demo entirely. There is no login
 * form to attack, no password published anywhere and no auth cookie, because
 * nothing authenticates.
 *
 * Auth::setUser() sets the user on the guard for this request only and writes
 * nothing to the session, which matters because the database is read-only: the
 * session guard's remember-me and logout paths both UPDATE the users row.
 */
class DemoVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::query()->orderBy('id')->first();

        if ($user !== null) {
            Auth::setUser($user);
        }

        return $next($request);
    }
}
