@extends('app')

@section('content')

    <div class="demo-intro">

        <h1 class="h3">{{ __('demo.title') }}</h1>

        <p class="demo-intro__lead">{{ __('demo.lead') }}</p>

        <h2 class="h5 mt-4">{{ __('demo.read_first') }}</h2>
        <ul class="demo-intro__list">
            @foreach (['invented', 'read_only', 'no_login', 'partial'] as $point)
                <li>
                    <strong>{{ __('demo.points.' . $point) }}</strong>
                    {{ __('demo.points.' . $point . '_body') }}
                </li>
            @endforeach
        </ul>

        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <h2 class="h5">{{ __('demo.can_look') }}</h2>
                <ul class="demo-intro__list">
                    @foreach (['ledgers', 'stock', 'reports', 'costs', 'reference'] as $item)
                        <li>{{ __('demo.can_look_items.' . $item) }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-12 col-md-6">
                <h2 class="h5">{{ __('demo.switched_off') }}</h2>
                <ul class="demo-intro__list">
                    @foreach (['writes', 'auth', 'forms'] as $item)
                        <li>{{ __('demo.switched_off_items.' . $item) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <p class="mt-4">{{ __('demo.freshness') }}</p>

        <p class="mt-4">
            <a class="btn btn-primary" href="{{ durl('/reports') }}">{{ __('app.nav.reports') }}</a>
            <a class="btn btn-outline-secondary" href="{{ durl('/bought-materials') }}">{{ __('app.nav.bought') }}</a>
        </p>

    </div>

@endsection
