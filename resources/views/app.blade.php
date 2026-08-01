<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">

    {{-- config(), not env(). Once config is cached - which is what the
         container does at build time - env() returns null outside the config
         files, and the original's `{{ env('APP_NAME') }}` left every page with
         an empty <title>. --}}
    <title>{{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    {{-- Served from this domain rather than a CDN, so the demo makes no
         third-party requests at all. jQuery is gone: the original loaded 90 KB
         of it for one form control. --}}
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/demo.css') }}" rel="stylesheet">
</head>

<body class="mb-5">

    <div class="demo-banner">
        {{ __('demo.banner') }}
        <span class="demo-lang">
            @foreach (['bg' => 'BG', 'en' => 'EN'] as $locale => $label)
                @if (app()->getLocale() === $locale)
                    <span aria-current="true">{{ $label }}</span>
                @else
                    <a href="{{ dswitch($locale) }}" hreflang="{{ $locale }}">{{ $label }}</a>
                @endif
            @endforeach
        </span>
    </div>

    @php
        $links = [
            'bought-materials' => 'app.nav.bought',
            'sorted-materials' => 'app.nav.sorted',
            'ground-materials' => 'app.nav.ground',
            'washed-materials' => 'app.nav.washed',
            'granular-materials' => 'app.nav.granular',
            'sold-materials' => 'app.nav.sold',
            'expenses' => 'app.nav.expenses',
            'salaries' => 'app.nav.salaries',
            'prepaid' => 'app.nav.prepaid',
            'available-materials' => 'app.nav.stock',
            'reports' => 'app.nav.reports',
            'others' => 'app.nav.reference',
        ];
    @endphp

    <nav class="navbar navbar-expand-xxl navbar-dark bg-dark">
        <div class="container-fluid">

            <a class="navbar-brand" href="{{ durl('/') }}">
                <img src="{{ asset('img/logo.svg') }}" width="34" height="34" alt="{{ config('app.name') }}">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler"
                aria-controls="navbarToggler" aria-expanded="false" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-auto">
                    @foreach ($links as $path => $label)
                        <li class="nav-item">
                            <a class="nav-link text-center {{ Request::path() === $path ? 'text-decoration-underline active' : '' }}"
                                @if (Request::path() === $path) aria-current="page" @endif
                                href="{{ durl('/' . $path) }}">{{ __($label) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="demo-footer">
        <a href="{{ durl('/') }}">{{ __('demo.about_link') }}</a>
        &middot;
        <a href="{{ durl('/privacy') }}">{{ __('demo.privacy_link') }}</a>
        &middot;
        {{ __('demo.built_by') }} <a href="https://wzco.net" rel="noopener">wzco.net</a>
    </footer>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/demo.js') }}" defer></script>
</body>

</html>
