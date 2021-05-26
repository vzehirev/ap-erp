@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Buy material form --}}
    <div class="container text-center">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeBoughtMaterial">
            Добави закупен материал +
        </button>
        <div class="modal fade" id="storeBoughtMaterial" tabindex="-1" aria-labelledby="storeBoughtMaterialLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeBoughtMaterialLabel">Добави закупен материал</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->hasBag('storeBoughtMaterial'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storeBoughtMaterial->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="d-flex text-center flex-column" action="/bought-material" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="bought_on" class="form-label">Дата</label>
                                <input type="date" class="form-control" id="bought_on" name="bought_on"
                                    value={{ old('bought_on') }}>
                            </div>
                            <div class="m-3">
                                <label for="partner_id" class="form-label">Закупен от</label>
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
                                <label for="material_id" class="form-label">Закупен материал</label>
                                <select class="form-select" id="material_id" name="material_id">
                                    <option selected>Избери материал</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name_and_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="text" class="form-control" id="price" name="price" value={{ old('price') }}>
                            </div>
                            <div class="m-3">
                                <label for="quantity" class="form-label">Закупено количество</label>
                                <input type="text" class="form-control" id="quantity" name="quantity"
                                    value={{ old('quantity') }}>
                            </div>
                            <div class="m-3">
                                <label for="invoice_num" class="form-label">Номер на фактура</label>
                                <input type="text" class="form-control" id="invoice_num" name="invoice_num"
                                    value={{ old('invoice_num') }}>
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

        {{-- Bought materials table --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Дата</th>
                        <th scope="col">Закупен от</th>
                        <th scope="col">Закупен материал</th>
                        <th scope="col">Код</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Закупено количество</th>
                        <th scope="col">Фактура №</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($boughtMaterials as $boughtMaterial)
                        <tr>
                            <td>{{ $boughtMaterial->bought_on }}</td>
                            <td>{{ $boughtMaterial->partner->name }}</td>
                            <td>{{ $boughtMaterial->material->name }}</td>
                            <td>{{ $boughtMaterial->material->code }}</td>
                            <td>{{ $boughtMaterial->price }}</td>
                            <td>{{ $boughtMaterial->quantity }}</td>
                            <td>{{ $boughtMaterial->invoice_num }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :lengthAwarePaginator="$boughtMaterials" />

    </div>

    <x-open_modal_on_error :viewErrorBag="$errors" />

@endsection
