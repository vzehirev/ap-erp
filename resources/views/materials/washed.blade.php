@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Store sorted material --}}
    <div class="container text-center">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeWashedMaterial">
            Добави изпран материал +
        </button>
        <div class="modal fade" id="storeWashedMaterial" tabindex="-1" aria-labelledby="storeWashedMaterialLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeWashedMaterialLabel">Добави изпран материал</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
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
                                <label for="washed_on" class="form-label">Дата*</label>
                                <input type="date" class="form-control" id="washed_on" name="washed_on"
                                    value="{{ old('washed_on') }}">
                            </div>
                            <div class="m-3">
                                <label for="worker_id" class="form-label">Изпран от*</label>
                                <select class="form-select" id="worker_id" name="worker_id">
                                    <option selected>Избери служител</option>
                                    @foreach ($workers as $worker)
                                        <option value="{{ $worker->id }}"
                                            {{ old('worker_id') == $worker->id ? 'selected' : '' }}>{{ $worker->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="from_material_id" class="form-label">От материал*</label>
                                <select class="form-select" id="from_material_id" name="from_material_id">
                                    <option selected>Избери материал</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('from_material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name_and_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="to_material_id" class="form-label">Получен материал*</label>
                                <select class="form-select" id="to_material_id" name="to_material_id">
                                    <option selected>Избери получен материал</option>
                                    @foreach ($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ old('to_material_id') == $material->id ? 'selected' : '' }}>
                                        {{ $material->name_and_code }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="quantity" class="form-label">Изпрано количество*</label>
                                <input type="text" class="form-control" id="quantity" name="quantity"
                                    value="{{ old('quantity') }}">
                            </div>
                            <div class="d-flex flex-row justify-content-center">
                                <button type="button" class="btn btn-outline-danger m-3"
                                    data-bs-dismiss="modal">Затвори</button>
                                <button type="submit" class="btn btn-success m-3">Добави</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sorted material table --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Дата</th>
                        <th scope="col">Изпран от</th>
                        <th scope="col">От материал</th>
                        <th scope="col">Получен материал</th>
                        <th scope="col">Изпрано количество</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($washedMaterials as $washedMaterial)
                        <tr>
                            <td>{{ $washedMaterial->washed_on }}</td>
                            <td>{{ $washedMaterial->worker->name }}
                            <td>{{ $washedMaterial->from_material->name_and_code }}</td>
                            <td>{{ $washedMaterial->to_material->name_and_code }}</td>
                            <td>{{ $washedMaterial->quantity }}</td>
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
