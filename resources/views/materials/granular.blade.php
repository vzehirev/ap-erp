@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Store sorted material --}}
    <div class="container text-center">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeGranularMaterial">
            Добави гранулиран материал +
        </button>
        <div class="modal fade" id="storeGranularMaterial" tabindex="-1" aria-labelledby="storeGranularMaterialLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeGranularMaterialLabel">Добави гранулиран материал</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
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
                                <label for="granular_on" class="form-label">Дата*</label>
                                <input type="date" class="form-control" id="granular_on" name="granular_on"
                                    value="{{ old('granular_on') }}">
                            </div>
                            <div class="m-3">
                                <label for="worker_id" class="form-label">Гранулиран от*</label>
                                <select class="form-select" id="worker_id" name="worker_id">
                                    <option selected>Избери служител</option>
                                    @foreach ($workers as $worker)
                                        <option value="{{ $worker->id }}"
                                            {{ old('worker_id') == $worker->id ? 'selected' : '' }}>{{ $worker->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="my-3 d-flex flex-row justify-content-between from-material">
                                <div>
                                    <label for="from_material_id" class="form-label">От материал*</label>
                                    <select class="form-select" id="from_material_id" name="from_materials[]">
                                        <option selected>Избери материал</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}"
                                                {{ old('from_material_id') == $material->id ? 'selected' : '' }}>
                                                {{ $material->name_and_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="quantity_before" class="form-label">Количество изпран материал*</label>
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
                                <label for="to_material_id" class="form-label">Получен материал*</label>
                                <select class="form-select" id="to_material_id" name="to_material_id">
                                    <option selected>Избери материал</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('to_material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name_and_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="quantity" class="form-label">Количество получена гранула*</label>
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
                        <th scope="col">Гранулиран от</th>
                        <th scope="col">От материал</th>
                        <th scope="col">Получен материал</th>
                        <th scope="col">Получено количество</th>
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
        $("#add-additional-from-material").click(() => {
            let lastFromMaterialEl = $(".from-material").last();
            $(lastFromMaterialEl).after($(lastFromMaterialEl.clone()));
            $(".from-material").last().click(removeParentEl);
        });

        function removeParentEl(el) {
            let target = $(el.target);
            if (target.hasClass("remove-additional-from-material")) {
                target.parent().remove();
            }
        };
    </script>
@endsection
