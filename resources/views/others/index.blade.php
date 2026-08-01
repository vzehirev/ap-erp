@extends('app')

@section('content')

    <div class="container my-4">

        @if (session('success'))
            <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
        @endif

        {{-- The three "add" modals, as in the original. --}}
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storePartner">
                {{ __('app.add.partner') }} +
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storeMaterial">
                {{ __('app.add.material') }} +
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storeWorker">
                {{ __('app.add.worker') }} +
            </button>
        </div>

        <div class="modal fade" id="storePartner" tabindex="-1" aria-labelledby="storePartnerLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storePartnerLabel">{{ __('app.add.partner') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('app.actions.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex text-center flex-column" action="/store-partner" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="partner_name" class="form-label">{{ __('app.fields.partner_name') }}*</label>
                                <input type="text" class="form-control" id="partner_name" name="name"
                                    value="{{ old('name') }}">
                            </div>
                            <div class="d-flex flex-row justify-content-center">
                                <button type="button" class="btn btn-outline-danger m-3"
                                    data-bs-dismiss="modal">{{ __('app.actions.close') }}</button>
                                <button type="submit" class="btn btn-success m-3">{{ __('app.actions.add') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="storeMaterial" tabindex="-1" aria-labelledby="storeMaterialLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeMaterialLabel">{{ __('app.add.material') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('app.actions.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex text-center flex-column" action="/store-material" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="material_name" class="form-label">{{ __('app.fields.material_name') }}*</label>
                                <input type="text" class="form-control" id="material_name" name="name"
                                    value="{{ old('name') }}">
                            </div>
                            <div class="m-3">
                                <label for="material_code" class="form-label">{{ __('app.fields.material_code') }}</label>
                                <input type="text" class="form-control" id="material_code" name="code"
                                    value="{{ old('code') }}">
                            </div>
                            <div class="d-flex flex-row justify-content-center">
                                <button type="button" class="btn btn-outline-danger m-3"
                                    data-bs-dismiss="modal">{{ __('app.actions.close') }}</button>
                                <button type="submit" class="btn btn-success m-3">{{ __('app.actions.add') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="storeWorker" tabindex="-1" aria-labelledby="storeWorkerLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeWorkerLabel">{{ __('app.add.worker') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('app.actions.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex text-center flex-column" action="/store-worker" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="worker_name" class="form-label">{{ __('app.fields.worker_name') }}*</label>
                                <input type="text" class="form-control" id="worker_name" name="name"
                                    value="{{ old('name') }}">
                            </div>
                            <div class="d-flex flex-row justify-content-center">
                                <button type="button" class="btn btn-outline-danger m-3"
                                    data-bs-dismiss="modal">{{ __('app.actions.close') }}</button>
                                <button type="submit" class="btn btn-success m-3">{{ __('app.actions.add') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Not in the original, where this page was three buttons and nothing
             else. With the buttons disabled that reads as an empty screen, so
             the demo lists what those three modals maintain. --}}
        <div class="row mt-4">
            <div class="col-12 col-lg-4">
                <h2 class="h5 text-center">{{ __('app.reference.partners') }}</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('app.fields.partner_name') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($partners as $partner)
                                <tr>
                                    <td class="align-middle">{{ $partner->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="align-middle text-muted">{{ __('app.reference.no_rows') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <h2 class="h5 text-center">{{ __('app.reference.materials') }}</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('app.fields.material_name') }}</th>
                                <th scope="col">{{ __('app.fields.code') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materials as $material)
                                <tr>
                                    <td class="align-middle">{{ $material->name }}</td>
                                    <td class="align-middle">{{ $material->code }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="align-middle text-muted" colspan="2">{{ __('app.reference.no_rows') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <h2 class="h5 text-center">{{ __('app.reference.workers') }}</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('app.fields.worker_name') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($workers as $worker)
                                <tr>
                                    <td class="align-middle">{{ $worker->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="align-middle text-muted">{{ __('app.reference.no_rows') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <x-open_modal_on_error :viewErrorBag="$errors" />

@endsection
