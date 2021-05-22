@extends('app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger w-50 mx-auto my-3 text-center" role="alert">Грешка, моля опитайте отново.</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Buy product form --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#buy-material-modal">
            Добави закупен материал
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
                                <input type="date" class="form-control @error('bought_on') border border-danger @enderror"
                                    id="bought_on" name="bought_on">
                                @error('bought_on')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="m-3">
                                <label for="partner_id" class="form-label">Закупен от</label>
                                <select class="form-select @error('partner_id') border border-danger @enderror"
                                    id="partner_id" name="partner_id">
                                    <option selected>Избери партньор</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                                @error('partner_id')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="m-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="text" class="form-control @error('price') border border-danger @enderror"
                                    id="price" name="price">
                                @error('price')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="m-3">
                                <label for="quantity" class="form-label">Количество</label>
                                <input type="text" class="form-control @error('quantity') border border-danger @enderror"
                                    id="quantity" name="quantity">
                                @error('quantity')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="m-3">
                                <label for="product_id" class="form-label">Закупен продукт</label>
                                <select class="form-select @error('product_id') border border-danger @enderror"
                                    id="product_id" name="product_id">
                                    <option selected>Избери продукт</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->code ? "$product->name" . " ($product->code)" : "$product->name" }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="m-3">
                                <label for="invoice_num" class="form-label">Номер на фактура</label>
                                <input type="text" class="form-control" id="invoice_num" name="invoice_num">
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
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Закупен на</th>
                    <th scope="col">Продукт</th>
                    <th scope="col">Код</th>
                    <th scope="col">Партньор</th>
                    <th scope="col">Цена</th>
                    <th scope="col">Количество</th>
                    <th scope="col">Фактура</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boughtMaterials as $boughtMaterial)
                    <tr>
                        <td>{{ $boughtMaterial->bought_on }}</td>
                        <td> {{ $boughtMaterial->product->name }}
                        </td>
                        <td>{{ $boughtMaterial->product->code }}</td>
                        <td>{{ $boughtMaterial->partner->name }}</td>
                        <td>{{ $boughtMaterial->price }}</td>
                        <td>{{ $boughtMaterial->quantity }}</td>
                        <td>{{ $boughtMaterial->invoice_num }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="?page=1" aria-label="Previous">
                        <span aria-hidden="true">Първа</span>
                    </a>
                </li>
                @for ($page = $boughtMaterials->currentPage() - 2; $page <= $boughtMaterials->currentPage() + 2; $page++)
                    @if ($page <= 0 || $page > $boughtMaterials->lastPage())
                        @continue
                    @endif
                    <li class="page-item @if ($boughtMaterials->currentPage() === $page) active @endif"><a class="page-link"
                            href="?page={{ $page }}">{{ $page }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="?page={{ $boughtMaterials->lastPage() }}" aria-label="Next">
                        <span aria-hidden="true">Последна</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
@endsection
