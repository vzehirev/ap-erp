@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Buy material form --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storePrepaid">
            Добави предплата
        </button>
        <div class="modal fade" id="storePrepaid" tabindex="-1" aria-labelledby="storePrepaidLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storePrepaidLabel">Добави предплата</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->hasBag('storePrepaid'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storePrepaid->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="d-flex text-center flex-column" action="/prepaid" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="paid_on" class="form-label">Дата</label>
                                <input type="date" class="form-control" id="paid_on" name="paid_on">
                            </div>
                            <div class="m-3">
                                <label for="worker_id" class="form-label">Служител</label>
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
                                <label for="price" class="form-label">Цена</label>
                                <input type="text" class="form-control" id="price" name="price">
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
                    <th scope="col">Дата</th>
                    <th scope="col">Служител</th>
                    <th scope="col">Сума</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prepaid as $pp)
                    <tr>
                        <td>{{ $pp->paid_on }}</td>
                        <td>{{ $pp->worker->name }}</td>
                        <td>{{ $pp->price }}</td>
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
                @for ($page = $prepaid->currentPage() - 2; $page <= $prepaid->currentPage() + 2; $page++)
                    @if ($page <= 0 || $page > $prepaid->lastPage())
                        @continue
                    @endif
                    <li class="page-item @if ($prepaid->currentPage() === $page) active @endif"><a class="page-link"
                            href="?page={{ $page }}">{{ $page }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="?page={{ $prepaid->lastPage() }}" aria-label="Next">
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
