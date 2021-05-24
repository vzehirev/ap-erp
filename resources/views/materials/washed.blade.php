@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Store sorted material --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeWashedMaterial">
            Добави изпран материал
        </button>
        <div class="modal fade" id="storeWashedMaterial" tabindex="-1" aria-labelledby="storeWashedMaterialLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeWashedMaterialLabel">Добави изпран материал</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->hasBag('storeWashedMaterial'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storeWashedMaterial->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif
                        <form class="d-flex text-center flex-column" action="/washed-material" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="washed_on" class="form-label">Изпран на</label>
                                <input type="date" class="form-control" id="washed_on" name="washed_on"
                                    value="{{ old('washed_on') }}">
                            </div>
                            <div class="m-3">
                                <label for="from_material_id" class="form-label">От материал</label>
                                <select class="form-select" id="from_material_id" name="from_material_id">
                                    <option selected>Избери материал</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('from_material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-3">
                                <label for="worker_id" class="form-label">Изпран от</label>
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
                                <label for="quantity" class="form-label">Изпрано количество</label>
                                <input type="text" class="form-control" id="quantity" name="quantity"
                                    value="{{ old('quantity') }}">
                            </div>
                            <div class="m-3">
                                <label for="to_material_id" class="form-label">Получен материал</label>
                                <select class="form-select" id="to_material_id" name="to_material_id">
                                    <option selected>Избери получен материал</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('to_material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                    <th scope="col">Изпран на</th>
                    <th scope="col">От материал</th>
                    <th scope="col">Изпран от</th>
                    <th scope="col">Изпрано количество</th>
                    <th scope="col">Получен материал</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($washedMaterials as $washedMaterial)
                    <tr>
                        <td>{{ $washedMaterial->washed_on }}</td>
                        <td>{{ $washedMaterial->from_material->name }}</td>
                        <td>{{ $washedMaterial->worker->name }}
                        <td>{{ $washedMaterial->quantity }}</td>
                        <td>{{ $washedMaterial->to_material->name }}</td>
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
                @for ($page = $washedMaterials->currentPage() - 2; $page <= $washedMaterials->currentPage() + 2; $page++)
                    @if ($page <= 0 || $page > $washedMaterials->lastPage())
                        @continue
                    @endif
                    <li class="page-item @if ($washedMaterials->currentPage() === $page) active @endif"><a class="page-link"
                            href="?page={{ $page }}">{{ $page }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="?page={{ $washedMaterials->lastPage() }}" aria-label="Next">
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
