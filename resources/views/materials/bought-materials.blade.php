@extends('app')

@section('content')
    <div class="container d-flex flex-column align-items-center mt-3">

        @if (session('success'))
            <div class="text-success text-center">{{ session('success') }}</div>
        @endif

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#buy-material-modal">
            Добави
        </button>
        <div class="modal fade" id="buy-material-modal" tabindex="-1" aria-labelledby="buy-material-modal-label"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buy-material-modal-label">Добави закупен материал</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex text-center flex-column" action="/bought-materials" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="bought_on" class="form-label">Закупен на</label>
                                <input type="date" class="form-control" id="bought_on" name="bought_on">
                            </div>
                            <div class="m-3">
                                <label for="partner_id" class="form-label">Закупен от</label>
                                <select class="form-select" id="partner_id" name="partner_id">
                                    <option selected>Избери партньор</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="text" class="form-control" id="price" name="price">
                            </div>
                            <div class="m-3">
                                <label for="quantity" class="form-label">Количество</label>
                                <input type="text" class="form-control" id="quantity" name="quantity">
                            </div>
                            <div class="m-3">
                                <label for="product_id" class="form-label">Закупен продукт</label>
                                <select class="form-select" id="product_id" name="product_id">
                                    <option selected>Избери продукт</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->code ? "$product->name" . " ($product->code)" : "$product->name" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="invoice_num" class="form-label">Номер на фактура</label>
                                <input type="text" class="form-control" id="invoice_num" name="invoice_num">
                            </div>
                            <div class="d-flex flex-row justify-content-center">
                                <button type="button" class="btn btn-outline-danger m-3"
                                    data-bs-dismiss="modal">Затвори</button>
                                <button type="submit" class="btn btn-primary m-3">Добави</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
