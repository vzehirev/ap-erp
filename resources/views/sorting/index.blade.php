@extends('app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger w-50 mx-auto my-3 text-center" role="alert">Грешка, моля опитайте отново.</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Store sorted material --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#sorted-material-modal">
            Добави сортиран материал
        </button>
        <div class="modal fade" id="sorted-material-modal" tabindex="-1" aria-labelledby="sorted-material-modal-label"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sorted-material-modal-label">Добави сортиран материал</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex text-center flex-column" action="/sorted-material" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="sorted_on" class="form-label">Сортирано на</label>
                                <input type="date" class="form-control @error('sorted_on') border border-danger @enderror"
                                    id="sorted_on" name="sorted_on">
                                @error('sorted_on')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="m-3">
                                <label for="partner_id" class="form-label">Партньор</label>
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
                                <label for="worker_id" class="form-label">Служител</label>
                                <select class="form-select @error('worker_id') border border-danger @enderror"
                                    id="worker_id" name="worker_id">
                                    <option selected>Избери служител</option>
                                    @foreach ($workers as $worker)
                                        <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                    @endforeach
                                </select>
                                @error('worker_id')
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
        {{-- <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Сортирано на</th>
                    <th scope="col">Партньор</th>
                    <th scope="col">Сортирано от</th>
                    <th scope="col">Количество</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sortedMaterials as $sortedMaterial)
                    <tr>
                        <td>{{ $sortedMaterial->sorted_on }}</td>
                        <td>{{ $sortedMaterial->partner->name }}</td>
                        <td>{{ $sortedMaterial->quantity }}</td>
                        <td>{{ $sortedMaterial->worker->name }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}

        {{-- Pagination --}}
        {{-- <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="?page=1" aria-label="Previous">
                        <span aria-hidden="true">Първа</span>
                    </a>
                </li>
                @for ($page = $sortedMaterials->currentPage() - 2; $page <= $sortedMaterials->currentPage() + 2; $page++)
                    @if ($page <= 0 || $page > $sortedMaterials->lastPage())
                        @continue
                    @endif
                    <li class="page-item @if ($sortedMaterials->currentPage() === $page) active @endif"><a class="page-link"
                            href="?page={{ $page }}">{{ $page }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="?page={{ $sortedMaterials->lastPage() }}" aria-label="Next">
                        <span aria-hidden="true">Последна</span>
                    </a>
                </li>
            </ul>
        </nav> --}}

    </div>
@endsection
