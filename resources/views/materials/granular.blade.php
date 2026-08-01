@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Store sorted material --}}
    <div class="container text-center">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeGranularMaterial">
            {{ __('app.add.granular') }} +
        </button>
        <div class="modal fade" id="storeGranularMaterial" tabindex="-1" aria-labelledby="storeGranularMaterialLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeGranularMaterialLabel">{{ __('app.add.granular') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('app.actions.close') }}"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->hasBag('storeGranularMaterial'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storeGranularMaterial->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="d-flex text-center flex-column" action="/granular-materials" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="granular_on" class="form-label">{{ __('app.fields.date') }}*</label>
                                <input type="date" class="form-control" id="granular_on" name="granular_on"
                                    value="{{ old('granular_on') }}">
                            </div>
                            <div class="m-3">
                                <label for="worker_id" class="form-label">{{ __('app.fields.granulated_by') }}*</label>
                                <select class="form-select" id="worker_id" name="worker_id">
                                    <option selected>{{ __('app.choose.worker') }}</option>
                                    @foreach ($workers as $worker)
                                        <option value="{{ $worker->id }}"
                                            {{ old('worker_id') == $worker->id ? 'selected' : '' }}>{{ $worker->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="my-3 d-flex flex-row justify-content-between from-material">
                                <div>
                                    <label for="from_material_id" class="form-label">{{ __('app.fields.from_material') }}*</label>
                                    <select class="form-select" id="from_material_id" name="from_materials[]">
                                        <option selected>{{ __('app.choose.material') }}</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}"
                                                {{ old('from_material_id') == $material->id ? 'selected' : '' }}>
                                                {{ $material->name_and_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mx-1 w-50">
                                    <label style="width: max-content;" for="quantity_before" class="form-label">{{ __('app.fields.washed_material_quantity') }}*</label>
                                    <input type="text" class="form-control" id="quantity_before" name="quantity_before[]"
                                        value="{{ old('quantity_before') }}">
                                </div>
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger align-self-end mb-1 remove-additional-from-material">X</button>
                            </div>
                            <div>
                                <button id="add-additional-from-material" type="button"
                                    class="btn btn-outline-primary btn-sm">+</button>
                            </div>
                            <div class="m-3">
                                <label for="to_material_id" class="form-label">{{ __('app.fields.to_material') }}*</label>
                                <select class="form-select" id="to_material_id" name="to_material_id">
                                    <option selected>{{ __('app.choose.material') }}</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('to_material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name_and_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="quantity" class="form-label">{{ __('app.fields.granulate_quantity') }}*</label>
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

        {{-- Sorted material table --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">{{ __('app.fields.date') }}</th>
                        <th scope="col">{{ __('app.fields.granulated_by') }}</th>
                        <th scope="col">{{ __('app.fields.from_material') }}</th>
                        <th scope="col">{{ __('app.fields.to_material') }}</th>
                        <th scope="col">{{ __('app.fields.quantity_received') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($granularMaterials as $granularMaterial)
                        <tr>
                            <td class="align-middle">{{ $granularMaterial->granular_on }}</td>
                            <td class="align-middle">{{ $granularMaterial->worker->name }}</td>
                            <td class="align-middle">{{ $granularMaterial->from_materials->implode('name_and_code', ', ') }}</td>
                            <td class="align-middle">{{ $granularMaterial->to_material->name_and_code }}</td>
                            <td class="align-middle">{{ $granularMaterial->quantity }}</td>
                            <td class="align-middle">
                                <form action="/delete-granular-material/{{ $granularMaterial->id }}" method="post">@csrf
                                    <button type="submit" id="confirm-delete" class="btn btn-outline-danger">X</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :lengthAwarePaginator="$granularMaterials" />

    </div>

    <x-open_modal_on_error :viewErrorBag="$errors" />

    <script>
        document.getElementById("add-additional-from-material").addEventListener("click", () => {
            let fromMaterialEls = document.querySelectorAll(".from-material");
            let lastFromMaterialEl = fromMaterialEls[fromMaterialEls.length - 1];
            lastFromMaterialEl.after(lastFromMaterialEl.cloneNode(true));
            let updatedFromMaterialEls = document.querySelectorAll(".from-material");
            updatedFromMaterialEls[updatedFromMaterialEls.length - 1].addEventListener("click", removeParentEl);
        });

        function removeParentEl(el) {
            let target = el.target;
            if (target.classList.contains("remove-additional-from-material")) {
                target.parentNode.remove();
            }
        };
    </script>
@endsection
