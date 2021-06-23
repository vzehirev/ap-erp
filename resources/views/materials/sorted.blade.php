@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Store sorted material --}}
    <div class="container text-center">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeSortedMaterial">
            Добави сортиран материал +
        </button>
        <div class="modal fade" id="storeSortedMaterial" tabindex="-1" aria-labelledby="storeSortedMaterialLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeSortedMaterialLabel">Добави сортиран материал</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->hasBag('storeSortedMaterial'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storeSortedMaterial->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="d-flex text-center flex-column" action="/sorted-materials" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="sorted_on" class="form-label">Дата*</label>
                                <input type="date" class="form-control" id="sorted_on" name="sorted_on"
                                    value="{{ old('sorted_on') }}">
                            </div>
                            <div class="btn-group m-3">
                                <button class="btn btn-primary dropdown-toggle" type="button"
                                    id="dropdownMenuClickableInside" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    Избери служител/и*
                                </button>
                                <div class="dropdown-menu w-100" aria-labelledby="dropdownMenuClickableInside">
                                    @foreach ($workers as $worker)
                                        <div class="ms-3 form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $worker->id }}"
                                                id="{{ $worker->id }}" name="workers[]">
                                            <label class="form-check-label" for="{{ $worker->id }}">
                                                {{ $worker->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
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
                                <label for="wasted_quantity" class="form-label">Изхвърлено количество (боклук)*</label>
                                <input type="text" class="form-control" id="wasted_quantity" name="wasted_quantity"
                                    value="{{ old('wasted_quantity') }}">
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
                                <label for="quantity" class="form-label">Получено количество*</label>
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
                        <th scope="col">Сортиран от</th>
                        <th scope="col">От материал</th>
                        <th scope="col">Получен материал</th>
                        <th scope="col">Получено количество</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sortedMaterials as $sortedMaterial)
                        <tr>
                            <td class="align-middle">{{ $sortedMaterial->sorted_on }}</td>
                            <td class="align-middle">{{ $sortedMaterial->workers->implode('name', ', ') }}</td>
                            <td class="align-middle">{{ $sortedMaterial->from_material->name_and_code }}</td>
                            <td class="align-middle">{{ $sortedMaterial->to_material->name_and_code }}</td>
                            <td class="align-middle">{{ $sortedMaterial->quantity }}</td>
                            <td class="align-middle">
                                <form action="/delete-sorted-material/{{ $sortedMaterial->id }}" method="post">@csrf
                                    <button type="submit" id="confirm-delete" class="btn btn-outline-danger">X</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :lengthAwarePaginator="$sortedMaterials" />

    </div>

    <x-open_modal_on_error :viewErrorBag="$errors" />

@endsection
