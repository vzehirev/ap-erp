@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Buy material form --}}
    <div class="container text-center">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeSoldMaterial">
            {{ __('app.add.sold') }} +
        </button>
        <div class="modal fade" id="storeSoldMaterial" tabindex="-1" aria-labelledby="storeSoldMaterialLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeSoldMaterialLabel">{{ __('app.add.sold') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('app.actions.close') }}"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->hasBag('storeSoldMaterial'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storeSoldMaterial->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="d-flex text-center flex-column" action="/sold-materials" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="sold_on" class="form-label">{{ __('app.fields.date') }}*</label>
                                <input type="date" class="form-control" id="sold_on" name="sold_on"
                                    value="{{ old('sold_on') }}">
                            </div>
                            <div class="m-3">
                                <label for="partner_id" class="form-label">{{ __('app.fields.sold_to') }}*</label>
                                <select class="form-select" id="partner_id" name="partner_id">
                                    <option selected>{{ __('app.choose.partner') }}</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}"
                                            {{ old('partner_id') == $partner->id ? 'selected' : '' }}>
                                            {{ $partner->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="material_id" class="form-label">{{ __('app.fields.sold_material') }}*</label>
                                <select class="form-select" id="material_id" name="material_id">
                                    <option selected>{{ __('app.choose.material') }}</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name_and_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="price" class="form-label">{{ __('app.fields.price') }}*</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    value="{{ old('price') }}">
                            </div>
                            <div class="m-3">
                                <label for="quantity" class="form-label">{{ __('app.fields.sold_quantity') }}*</label>
                                <input type="text" class="form-control" id="quantity" name="quantity"
                                    value="{{ old('quantity') }}">
                            </div>
                            <div class="m-3">
                                <input class="form-check-input" type="checkbox" id="paid" name="paid" value="1"
                                    {{ old('paid') ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckDefault">{{ __('app.fields.paid') }}</label>
                            </div>
                            <div class="m-3">
                                <label for="invoice_num" class="form-label">{{ __('app.fields.invoice_number') }}</label>
                                <input type="text" class="form-control" id="invoice_num" name="invoice_num"
                                    value="{{ old('invoice_num') }}">
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
                        <th scope="col">{{ __('app.fields.sold_to') }}</th>
                        <th scope="col">{{ __('app.fields.sold_material') }}</th>
                        <th scope="col">{{ __('app.fields.code') }}</th>
                        <th scope="col">{{ __('app.fields.price') }}</th>
                        <th scope="col">{{ __('app.fields.sold_quantity') }}</th>
                        <th scope="col">{{ __('app.fields.paid') }}</th>
                        <th scope="col">{{ __('app.fields.invoice') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($soldMaterials as $soldMaterial)
                        <tr>
                            <td class="align-middle">{{ $soldMaterial->sold_on }}</td>
                            <td class="align-middle">{{ $soldMaterial->partner->name }}</td>
                            <td class="align-middle">{{ $soldMaterial->material->name }}</td>
                            <td class="align-middle">{{ $soldMaterial->material->code }}</td>
                            <td class="align-middle">{{ $soldMaterial->price }}</td>
                            <td class="align-middle">{{ $soldMaterial->quantity }}</td>
                            <td class="align-middle">{{ $soldMaterial->paid ? __('app.yes') : __('app.no') }}</td>
                            <td class="align-middle">{{ $soldMaterial->invoice_num }}</td>
                            <td class="align-middle">
                                <form action="/delete-sold-material/{{ $soldMaterial->id }}" method="post">@csrf
                                    <button type="submit" id="confirm-delete" class="btn btn-outline-danger">X</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :lengthAwarePaginator="$soldMaterials" />

    </div>

    <x-open_modal_on_error :viewErrorBag="$errors" />

@endsection
