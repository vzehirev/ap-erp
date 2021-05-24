@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Store sorted material --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeGranularMaterial">
            Добави гранулиран материал
        </button>
        <div class="modal fade" id="storeGranularMaterial" tabindex="-1" aria-labelledby="storeGranularMaterialLabel"
            aria-hidden="true">
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
                        <form class="d-flex text-center flex-column" action="/granular-material" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="granular_on" class="form-label">Гранулиран на</label>
                                <input type="date" class="form-control" id="granular_on" name="granular_on"
                                    value="{{ old('granular_on') }}">
                            </div>
                            <div class="m-3">
                                <label for="worker_id" class="form-label">Гранулиран от</label>
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
                                <label for="material_id" class="form-label">Материал</label>
                                <select class="form-select" id="material_id" name="material_id">
                                    <option selected>Избери материал</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="quantity" class="form-label">Гранулирано количество</label>
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
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Гранулиран на</th>
                    <th scope="col">Гранулиран от</th>
                    <th scope="col">Материал</th>
                    <th scope="col">Гранулирано количество</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($granularMaterials as $granularMaterial)
                    <tr>
                        <td>{{ $granularMaterial->granular_on }}</td>
                        <td>{{ $granularMaterial->worker->name }}
                        <td>{{ $granularMaterial->material->name }}</td>
                        <td>{{ $granularMaterial->quantity }}</td>
                        </td>
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
                @for ($page = $granularMaterials->currentPage() - 2; $page <= $granularMaterials->currentPage() + 2; $page++)
                    @if ($page <= 0 || $page > $granularMaterials->lastPage())
                        @continue
                    @endif
                    <li class="page-item @if ($granularMaterials->currentPage() === $page) active @endif"><a class="page-link"
                            href="?page={{ $page }}">{{ $page }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="?page={{ $granularMaterials->lastPage() }}" aria-label="Next">
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
