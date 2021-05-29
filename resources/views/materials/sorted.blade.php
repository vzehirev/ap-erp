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
                            <div class="m-3">
                                <label for="partner_id" class="form-label">Закупен от*</label>
                                <select class="form-select" id="partner_id" name="partner_id">
                                    <option selected>Избери партньор</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}"
                                            {{ old('partner_id') == $partner->id ? 'selected' : '' }}>
                                            {{ $partner->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="worker_id" class="form-label">Сортиран от*</label>
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
                                <label for="quantity" class="form-label">Сортирано количество*</label>
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
                        <th scope="col">Закупен от</th>
                        <th scope="col">Сортиран от</th>
                        <th scope="col">Сортирано количество</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sortedMaterials as $sortedMaterial)
                        <tr>
                            <td>{{ $sortedMaterial->sorted_on }}</td>
                            <td>{{ $sortedMaterial->partner->name }}</td>
                            <td>{{ $sortedMaterial->worker->name }}
                            <td>{{ $sortedMaterial->quantity }}</td>
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
