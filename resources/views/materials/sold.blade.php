@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Buy material form --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeSoldMaterial">
            Добави продаден материал
        </button>
        <div class="modal fade" id="storeSoldMaterial" tabindex="-1" aria-labelledby="storeSoldMaterialLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeSoldMaterialLabel">Добави продаден материал</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->hasBag('storeSoldMaterial'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storeSoldMaterial->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="d-flex text-center flex-column" action="/sold-material" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="sold_on" class="form-label">Продаден на</label>
                                <input type="date" class="form-control" id="sold_on" name="sold_on">
                            </div>
                            <div class="m-3">
                                <label for="partner_id" class="form-label">Продаден на</label>
                                <select class="form-select" id="partner_id" name="partner_id">
                                    <option selected>Избери партньор</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="material_id" class="form-label">Продаден материал</label>
                                <select class="form-select" id="material_id" name="material_id">
                                    <option selected>Избери материал</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}">
                                            {{ $material->code ? "$material->name" . " ($material->code)" : "$material->name" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="quantity" class="form-label">Количество</label>
                                <input type="text" class="form-control" id="quantity" name="quantity">
                            </div>
                            <div class="m-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="text" class="form-control" id="price" name="price">
                            </div>
                            <div class="m-3">
                                <label for="paid" class="form-label">Платен</label>
                                <input type="text" class="form-control" id="paid" name="paid">
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
                    <th scope="col">Продаден на</th>
                    <th scope="col">Продаден на</th>
                    <th scope="col">Материал</th>
                    <th scope="col">Код</th>
                    <th scope="col">Количество</th>
                    <th scope="col">Цена</th>
                    <th scope="col">Платен</th>
                    <th scope="col">Фактура</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($soldMaterials as $soldMaterial)
                    <tr>
                        <td>{{ $soldMaterial->sold_on }}</td>
                        <td>{{ $soldMaterial->partner->name }}</td>
                        <td>{{ $soldMaterial->material->name }}</td>
                        <td>{{ $soldMaterial->material->code }}</td>
                        <td>{{ $soldMaterial->quantity }}</td>
                        <td>{{ $soldMaterial->price }}</td>
                        <td>{{ $soldMaterial->paid }}</td>
                        <td>{{ $soldMaterial->invoice_num }}</td>
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
                @for ($page = $soldMaterials->currentPage() - 2; $page <= $soldMaterials->currentPage() + 2; $page++)
                    @if ($page <= 0 || $page > $soldMaterials->lastPage())
                        @continue
                    @endif
                    <li class="page-item @if ($soldMaterials->currentPage() === $page) active @endif"><a class="page-link"
                            href="?page={{ $page }}">{{ $page }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="?page={{ $soldMaterials->lastPage() }}" aria-label="Next">
                        <span aria-hidden="true">Последна</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>

    {{-- Automatically show the modal, if form validaiton fails --}}
    @if (count($errors->getBags()) > 0)
        <script>
            showModal("{{ array_key_first($errors->getBags()) }}");

        </script>
    @endif
@endsection
