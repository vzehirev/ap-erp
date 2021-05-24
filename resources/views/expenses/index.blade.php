@extends('app')

@section('content')

    @if (session('success'))
        <div class="alert alert-success w-50 mx-auto my-3 text-center" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Buy material form --}}
    <div class="container d-flex flex-column align-items-center mt-3">
        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#storeExpense">
            Добави разход
        </button>
        <div class="modal fade" id="storeExpense" tabindex="-1" aria-labelledby="storeExpenseLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeExpenseLabel">Добави разход</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
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
                                <label for="made_on" class="form-label">Дата</label>
                                <input type="date" class="form-control" id="made_on" name="made_on">
                            </div>
                            <div class="m-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="text" class="form-control" id="price" name="price">
                            </div>
                            <div class="m-3">
                                <label for="type" class="form-label">Вид</label>
                                <input type="text" class="form-control" id="type" name="type">
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
                    <th scope="col">Вид</th>
                    <th scope="col">Цена</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($madeExpenses as $madeExpense)
                    <tr>
                        <td>{{ $madeExpense->made_on }}</td>
                        <td>{{ $madeExpense->type }}</td>
                        <td>{{ $madeExpense->price }}</td>
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
                @for ($page = $madeExpenses->currentPage() - 2; $page <= $madeExpenses->currentPage() + 2; $page++)
                    @if ($page <= 0 || $page > $madeExpenses->lastPage())
                        @continue
                    @endif
                    <li class="page-item @if ($madeExpenses->currentPage() === $page) active @endif"><a class="page-link"
                            href="?page={{ $page }}">{{ $page }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="?page={{ $madeExpenses->lastPage() }}" aria-label="Next">
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
