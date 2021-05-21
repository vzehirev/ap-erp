@extends('app')

@section('content')

    <h2 class="text-center mt-3">Влез в профила си</h2>

    @if (session('error'))
        <p class="text-danger text-center">{{ session('error') }}</p>
    @endif

    <form class="col-8 col-md-4 text-center mx-auto mt-5" action="/login" method="post">

        @csrf

        <div class="mb-5">
            <input type="text" class="form-control @error('username_or_email') border border-danger @enderror"
                name=" username_or_email" placeholder="Вашето потребителско име или имейл"
                value="{{ old('username_or_email') }}">
            @error('username_or_email')
                <p class="text-danger mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <input type="password" class="form-control  @error('password') border border-danger @enderror" name="password"
                placeholder="Вашата парола">
            @error('password')
                <p class="text-danger mt-1">{{ $message }}</p>
            @enderror
        </div>

        <p class="text-secondary mb-5">Всички полета са задължителни.</p>

        <div class="mb-3 form-check d-flex justify-content-center">
            <input id="remember_me" class="form-check-input" type="checkbox" name="remember_me">
            <label class="form-check-label ms-1" for="remember_me">Запомни ме</label>
        </div>

        <button type="submit" class="btn btn-primary">Влез</button>
    </form>

@endsection
