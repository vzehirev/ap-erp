@extends('app')

@section('content')

<h2 class="text-center mt-3">Влез в профила си</h2>

@if(session('error'))
<p class="text-danger text-center">{{ session('error') }}</p>
@endif

<form class="col-8 col-md-4 text-center mx-auto mt-5" action="/login" method="post">

    @csrf

    <div class="mb-5">
        <input type="text" class="form-control @error('usernameOrEmail') border border-danger @enderror" name=" usernameOrEmail" placeholder="Вашето потребителско име или имейл" value="{{ old('usernameOrEmail') }}">
        @error('usernameOrEmail')
        <p class="text-danger mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <input type="password" class="form-control  @error('password') border border-danger @enderror" name="password" placeholder="Вашата парола">
        @error('password')
        <p class="text-danger mt-1">{{ $message }}</p>
        @enderror
    </div>

    <p class="text-secondary mb-5">Всички полета са задължителни.</p>

    <div class="mb-3">
        <input id="rememberMe" class="form-check-input" type="checkbox" name="rememberMe">
        <label for="rememberMe">Запомни ме</label>
    </div>

    <button type="submit" class="btn btn-primary">Влез</button>
</form>

@endsection