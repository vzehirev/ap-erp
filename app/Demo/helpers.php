<?php

use App\Http\Middleware\SetDemoLocale;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;

if (! function_exists('durl')) {
    /**
     * An internal link that keeps the chosen language.
     *
     * The demo carries its locale in the query string rather than a cookie, so
     * every link the app renders has to pass it on. Bulgarian is the original
     * language and the default, so its links stay clean.
     *
     * @param  array<string, string|int>  $query
     */
    function durl(string $path = '/', array $query = []): string
    {
        if (App::getLocale() !== SetDemoLocale::DEFAULT) {
            $query['lang'] = App::getLocale();
        }

        return $query === [] ? $path : $path.'?'.http_build_query($query);
    }
}

if (! function_exists('dswitch')) {
    /**
     * The current page in the other language.
     */
    function dswitch(string $locale): string
    {
        $query = Request::query();
        unset($query['lang']);

        if ($locale !== SetDemoLocale::DEFAULT) {
            $query['lang'] = $locale;
        }

        $path = Request::getPathInfo();

        return $query === [] ? $path : $path.'?'.http_build_query($query);
    }
}
