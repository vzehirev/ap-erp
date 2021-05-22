@extends('app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger w-50 mx-auto my-3 text-center" role="alert">Грешка, моля опитайте отново.</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Add partner --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-partner-modal">
            Добави партньор
        </button>
        <div class="modal fade" id="add-partner-modal" tabindex="-1" aria-labelledby="add-partner-modal-label"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add-partner-modal-label">Добави партньор</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <form class="d-flex text-center flex-column" action="/add-partner" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="m-3">
                                <label for="name" class="form-label">Име</label>
                                <input type="text" class="form-control @error('name') border border-danger @enderror"
                                    id="name" name="name" value={{ old('name') }}>
                                @error('name')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="button" class="btn btn-outline-danger m-3"
                                data-bs-dismiss="modal">Затвори</button>
                            <button type="submit" class="btn btn-success m-3">Добави партньор</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add product --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-product-modal">
            Добави продукт
        </button>
        <div class="modal fade" id="add-product-modal" tabindex="-1" aria-labelledby="add-product-modal-label"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add-product-modal-label">Добави продукт</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <form class="d-flex text-center flex-column" action="/add-product" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="m-3">
                                <label for="name" class="form-label">Име</label>
                                <input type="text" class="form-control @error('name') border border-danger @enderror"
                                    id="name" name="name" value={{ old('name') }}>
                                @error('name')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="m-3">
                                <label for="name" class="form-label">Код</label>
                                <input type="text" class="form-control @error('code') border border-danger @enderror"
                                    id="code" name="code" value={{ old('code') }}>
                                @error('code')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="button" class="btn btn-outline-danger m-3"
                                data-bs-dismiss="modal">Затвори</button>
                            <button type="submit" class="btn btn-success m-3">Добави продукт</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        {{-- Add worker --}}
        <div class="container d-flex flex-column align-items-center mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-worker-modal">
                Добави служител
            </button>
            <div class="modal fade" id="add-worker-modal" tabindex="-1" aria-labelledby="add-product-worker-label"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="add-worker-modal-label">Добави служител</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                        </div>
                        <form class="d-flex text-center flex-column" action="/add-worker" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="m-3">
                                    <label for="name" class="form-label">Име</label>
                                    <input type="text" class="form-control @error('name') border border-danger @enderror"
                                        id="name" name="name" value={{ old('name') }}>
                                    @error('name')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="button" class="btn btn-outline-danger m-3"
                                    data-bs-dismiss="modal">Затвори</button>
                                <button type="submit" class="btn btn-success m-3">Добави служител</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
