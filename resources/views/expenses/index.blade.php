@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Buy material form --}}
    <div class="container text-center">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeExpense">
            {{ __('app.add.expense') }} +
        </button>
        <div class="modal fade" id="storeExpense" tabindex="-1" aria-labelledby="storeExpenseLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeExpenseLabel">{{ __('app.add.expense') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('app.actions.close') }}"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->hasBag('storeExpense'))
                            <div class="alert alert-danger mx-auto text-center mt-3 mb-0" role="alert">
                                @foreach ($errors->storeExpense->all() as $message)
                                    <p class="mb-0">{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="d-flex text-center flex-column" action="/expenses" method="post">
                            @csrf
                            <div class="m-3">
                                <label for="made_on" class="form-label">{{ __('app.fields.date') }}*</label>
                                <input type="date" class="form-control" id="made_on" name="made_on"
                                    value="{{ old('made_on') }}">
                            </div>
                            <div class="m-3">
                                <label for="type" class="form-label">{{ __('app.fields.expense_type') }}*</label>
                                <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}">
                            </div>
                            <div class="m-3">
                                <label for="price" class="form-label">{{ __('app.fields.price') }}*</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    value="{{ old('price') }}">
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
                        <th scope="col">{{ __('app.fields.expense_type') }}</th>
                        <th scope="col">{{ __('app.fields.price') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($madeExpenses as $expense)
                        <tr>
                            <td class="align-middle">{{ $expense->made_on }}</td>
                            <td class="align-middle">{{ $expense->type }}</td>
                            <td class="align-middle">{{ $expense->price }}</td>
                            <td class="align-middle">
                                <form action="/delete-expense/{{ $expense->id }}" method="post">@csrf
                                    <button type="submit" id="confirm-delete" class="btn btn-outline-danger">X</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :lengthAwarePaginator="$madeExpenses" />

    </div>

    <x-open_modal_on_error :viewErrorBag="$errors" />

@endsection
