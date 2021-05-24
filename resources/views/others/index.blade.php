@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Add partner --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storePartner">
            Добави партньор +
        </button>
        <div class="modal fade" id="storePartner" tabindex="-1" aria-labelledby="storePartnerLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storePartnerLabel">Добави партньор</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    @if ($errors->hasBag('storePartner'))
                        <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                            @foreach ($errors->storePartner->all() as $message)
                                <p class="mb-0">{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif
                    <form class="d-flex text-center flex-column" action="/store-partner" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="m-3">
                                <label for="name" class="form-label">Име на партньор</label>
                                <input type="text" class="form-control" id="name" name="name" value={{ old('name') }}>
                            </div>
                            <button type="button" class="btn btn-outline-danger m-3"
                                data-bs-dismiss="modal">Затвори</button>
                            <button type="submit" class="btn btn-success m-3">Добави партньор</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add material --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storeMaterial">
            Добави материал +
        </button>
        <div class="modal fade" id="storeMaterial" tabindex="-1" aria-labelledby="storeMaterialLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeMaterialLabel">Добави материал</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    @if ($errors->hasBag('storeMaterial'))
                        <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                            @foreach ($errors->storeMaterial->all() as $message)
                                <p class="mb-0">{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif
                    <form class="d-flex text-center flex-column" action="/store-material" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="m-3">
                                <label for="name" class="form-label">Име на материал</label>
                                <input type="text" class="form-control" id="name" name="name" value={{ old('name') }}>
                            </div>
                            <div class="m-3">
                                <label for="name" class="form-label">Код на материал</label>
                                <input type="text" class="form-control" id="code" name="code" value={{ old('code') }}>
                            </div>
                            <button type="button" class="btn btn-outline-danger m-3"
                                data-bs-dismiss="modal">Затвори</button>
                            <button type="submit" class="btn btn-success m-3">Добави материал</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add worker --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storeWorker">
            Добави служител +
        </button>
        <div class="modal fade" id="storeWorker" tabindex="-1" aria-labelledby="storeWorkerLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeWorkerLabel">Добави служител</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    @if ($errors->hasBag('storeWorker'))
                        <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                            @foreach ($errors->storeWorker->all() as $message)
                                <p class="mb-0">{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif
                    <form class="d-flex text-center flex-column" action="/store-worker" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="m-3">
                                <label for="name" class="form-label">Име на служител</label>
                                <input type="text" class="form-control" id="name" name="name" value={{ old('name') }}>
                            </div>
                            <button type="button" class="btn btn-outline-danger m-3"
                                data-bs-dismiss="modal">Затвори</button>
                            <button type="submit" class="btn btn-success m-3">Добави служител</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Automatically show the modal, if form validaiton fails --}}
    @if (count($errors->getBags()) > 0)
        <script>
            showModal("{{ array_key_first($errors->getBags()) }}");

        </script>
    @endif
@endsection
