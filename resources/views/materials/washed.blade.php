@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Store sorted material --}}
    <div class="container text-center">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeWashedMaterial">
            {{ __('app.add.washed') }} +
        </button>
        <div class="modal fade" id="storeWashedMaterial" tabindex="-1" aria-labelledby="storeWashedMaterialLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeWashedMaterialLabel">{{ __('app.add.washed') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('app.actions.close') }}"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->hasBag('storeWashedMaterial'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storeWashedMaterial->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="d-flex text-center flex-column" action="/washed-materials" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="washed_on" class="form-label">{{ __('app.fields.date') }}*</label>
                                <input type="date" class="form-control" id="washed_on" name="washed_on"
                                    value="{{ old('washed_on') }}">
                            </div>
                            <div class="m-3">
                                <label for="worker_id" class="form-label">{{ __('app.fields.washed_by') }}*</label>
                                <select class="form-select" id="worker_id" name="worker_id">
                                    <option selected>{{ __('app.choose.worker') }}</option>
                                    @foreach ($workers as $worker)
                                        <option value="{{ $worker->id }}"
                                            {{ old('worker_id') == $worker->id ? 'selected' : '' }}>{{ $worker->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="from_material_id" class="form-label">{{ __('app.fields.from_material') }}*</label>
                                <select class="form-select" id="from_material_id" name="from_material_id">
                                    <option selected>{{ __('app.choose.material') }}</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('from_material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name_and_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="quantity_before"
                                    class="form-label">{{ __('app.fields.washed_quantity_before') }}*</label>
                                <input type="text" class="form-control" id="quantity_before" name="quantity_before"
                                    value="{{ old('quantity_before') }}">
                            </div>
                            <div class="m-3">
                                <label for="to_material_id" class="form-label">{{ __('app.fields.to_material') }}*</label>
                                <select class="form-select" id="to_material_id" name="to_material_id">
                                    <option selected>{{ __('app.choose.to_material') }}</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('to_material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name_and_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="quantity"
                                    class="form-label">{{ __('app.fields.washed_quantity_after') }}*</label>
                                <input type="text" class="form-control" id="quantity" name="quantity"
                                    value="{{ old('quantity') }}">
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

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">{{ __('app.fields.date') }}</th>
                        <th scope="col">{{ __('app.fields.washed_by') }}</th>
                        <th scope="col">{{ __('app.fields.from_material') }}</th>
                        <th scope="col">{{ __('app.fields.to_material') }}</th>
                        <th scope="col">{{ __('app.fields.quantity_received') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($washedMaterials as $washedMaterial)
                        <tr>
                            <td class="align-middle">{{ $washedMaterial->washed_on }}</td>
                            <td class="align-middle">{{ $washedMaterial->worker->name }}</td>
                            <td class="align-middle">{{ $washedMaterial->from_material->name_and_code }}</td>
                            <td class="align-middle">{{ $washedMaterial->to_material->name_and_code }}</td>
                            <td class="align-middle">{{ $washedMaterial->quantity }}</td>
                            <td class="align-middle">
                                <form action="/delete-washed-material/{{ $washedMaterial->id }}" method="post">@csrf
                                    <button type="submit" id="confirm-delete" class="btn btn-outline-danger">X</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :lengthAwarePaginator="$washedMaterials" />

    </div>

    <x-open_modal_on_error :viewErrorBag="$errors" />

@endsection
