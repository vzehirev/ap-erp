@extends('app')

@section('content')

<h2 class="text-center mt-3">Регистрирай се</h2>

<form class="col-8 col-md-4 text-center mx-auto mt-5" action="/register" method="post">

    @csrf

    <div class="mb-5">
        <input type="email" class="form-control @error('email') border border-danger @enderror" name="email" placeholder="Вашият имейл" value="{{ old('email') }}">
        @error('email')
        <p class="text-danger mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <input type="email" class="form-control" name="email_confirmation" placeholder="Вашият имейл отново">
    </div>

    <div class="mb-5">
        <input type="text" class="form-control @error('username') border border-danger @enderror" name="username" placeholder="Вашето кратко потребителско име" value="{{ old('username') }}">
        @error('username')
        <p class="text-danger mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <input type="password" class="form-control @error('password') border border-danger @enderror" name="password" placeholder="Вашата парола">
        @error('password')
        <p class="text-danger mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Вашата парола отново">
    </div>

    <p class="text-secondary mb-5">Всички полета са задължителни.</p>

    <button type="submit" class="btn btn-primary">Регистрация</button>
</form>

@endsection