@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Buy material form --}}
    <div class="container text-center">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeSalary">
            Добави заплата +
        </button>
        <div class="modal fade" id="storeSalary" tabindex="-1" aria-labelledby="storeSalaryLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeSalaryLabel">Добави заплата</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->hasBag('storeSalary'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storeSalary->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="d-flex text-center flex-column" action="/salaries" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="date" class="form-label">Дата*</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}">
                            </div>
                            <div class="m-3">
                                <label for="worker_id" class="form-label">Служител*</label>
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
                                <label for="price" class="form-label">Сума*</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    value="{{ old('price') }}">
                            </div>
                            <div class="m-3">
                                <input class="form-check-input" type="checkbox" id="paid" name="paid" value="1"
                                    {{ old('paid') ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckDefault">Платена</label>
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
                        <th scope="col">Служител</th>
                        <th scope="col">Сума</th>
                        <th scope="col">Платена</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salaries as $salary)
                        <tr>
                            <td>{{ $salary->date }}</td>
                            <td>{{ $salary->worker->name }}</td>
                            <td>{{ $salary->price }}</td>
                            <td>{{ $salary->paid ? 'Да' : 'Не' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :lengthAwarePaginator="$salaries" />

    </div>

    <x-open_modal_on_error :viewErrorBag="$errors" />

@endsection
