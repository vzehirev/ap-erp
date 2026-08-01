@extends('app')

@section('content')

    <div class="demo-intro">

        <h1 class="h3">{{ __('demo.privacy.title') }}</h1>

        <p class="demo-intro__lead">{{ __('demo.privacy.lead') }}</p>

        <h2 class="h5 mt-4">{{ __('demo.privacy.does_not') }}</h2>
        <ul class="demo-intro__list">
            @foreach (['no_forms', 'no_tracking', 'no_writes'] as $item)
                <li>{{ __('demo.privacy.does_not_items.' . $item) }}</li>
            @endforeach
        </ul>

        <h2 class="h5 mt-4">{{ __('demo.privacy.does') }}</h2>
        <ul class="demo-intro__list">
            @foreach (['cookies', 'logs'] as $item)
                <li>{{ __('demo.privacy.does_items.' . $item) }}</li>
            @endforeach
        </ul>

        <h2 class="h5 mt-4">{{ __('demo.privacy.data') }}</h2>
        <p>{{ __('demo.privacy.data_body') }}</p>

        <p class="mt-4">
            <a class="btn btn-primary" href="{{ durl('/') }}">{{ __('demo.privacy.back') }}</a>
        </p>

    </div>

@endsection
