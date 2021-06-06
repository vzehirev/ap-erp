@extends('app')

@section('content')
    <div class="container mt-5 text-center">

        <form id="materials-reports-form" class="col-12 col-md-4 mx-auto" method="post" action="/reports">
            @csrf
            <div class="m-3">
                <label for="from_date" class="form-label">От дата</label>
                <input type="date" class="form-control" id="from_date" name="from_date" value={{ $from_date }}>
            </div>
            <div class="m-3">
                <label for="to_date" class="form-label">До дата</label>
                <input type="date" class="form-control" id="to_date" name="to_date" value={{ $to_date }}>
            </div>
            <div class="m-3 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">OK</button>
            </div>
        </form>
        <div class="m-3 d-flex justify-content-center">
            <a href="/reports"><button type="button" class="btn btn-outline-danger btn-sm">Изчисти периода</button></a>
        </div>

        <div class="fs-5 text-primary">Дании за периода:
            <strong>{{ $from_date ? Carbon\Carbon::parse($from_date)->format('d-M-Y') : 'От началото' }} -
                {{ $to_date ? Carbon\Carbon::parse($to_date)->format('d-M-Y') : 'До днес' }}</strong>
        </div>

        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingOne">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false"
                        aria-controls="flush-collapseOne">
                        <div class="mb-3 text-decoration-underline">Закупени материали</div>
                        <div>
                            <p class="mx-3 my-0">Общо количество:
                                <strong>{{ $boughtMaterialsTotal[0]->quantity ?? 0 }}</strong>
                            </p>
                            <p class="mx-3 my-0">Обща цена: <strong>{{ $boughtMaterialsTotal[0]->price ?? 0 }}</strong>
                            </p>
                            <p class="mx-3 my-0">Средна цена:
                                <strong>{{ round($boughtMaterialsTotal[0]->avg_price, 2) ?? 0 }}</strong>
                            </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Материал</th>
                                        <th scope="col">Код</th>
                                        <th scope="col">Закупено количество</th>
                                        <th scope="col">Средна цена</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($boughtMaterials as $boughtMaterial)
                                        <tr>
                                            <td>{{ $boughtMaterial->name }}</td>
                                            <td>{{ $boughtMaterial->code }}</td>
                                            <td>{{ $boughtMaterial->quantity }}</td>
                                            <td>{{ round($boughtMaterial->avg_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingTwo">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false"
                        aria-controls="flush-collapseTwo">
                        <div class="mb-3 text-decoration-underline">Бракувани материали</div>
                        <div>
                            <p class="mx-3 my-0">Общо количество:
                                <strong>{{ $wastedMaterialsTotal[0]->quantity ?? 0 }}</strong>
                            </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Материал</th>
                                        <th scope="col">Код</th>
                                        <th scope="col">Бракувано количество</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wastedMaterials as $wastedMaterial)
                                        <tr>
                                            <td>{{ $wastedMaterial->name }}</td>
                                            <td>{{ $wastedMaterial->code }}</td>
                                            <td>{{ $wastedMaterial->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingThree">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false"
                        aria-controls="flush-collapseThree">
                        <div class="mb-3 text-decoration-underline">Сортирани материали</div>
                        <div>
                            <p class="mx-3 my-0">Общо количество:
                                <strong>{{ $sortedMaterialsTotal[0]->quantity ?? 0 }}</strong>
                            </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">От материал</th>
                                        <th scope="col">Код (от)</th>
                                        <th scope="col">Получен материал</th>
                                        <th scope="col">Код (получен)</th>
                                        <th scope="col">Получено количество</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sortedMaterials as $sortedMaterial)
                                        <tr>
                                            <td>{{ $sortedMaterial->from_material_name }}</td>
                                            <td>{{ $sortedMaterial->from_material_code }}</td>
                                            <td>{{ $sortedMaterial->to_material_name }}</td>
                                            <td>{{ $sortedMaterial->to_material_code }}</td>
                                            <td>{{ $sortedMaterial->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingFour">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false"
                        aria-controls="flush-collapseFour">
                        <div class="mb-3 text-decoration-underline">Смляни материали</div>
                        <div>
                            <p class="mx-3 my-0">Общо количество:
                                <strong>{{ $groundMaterialsTotal[0]->quantity ?? 0 }}</strong>
                            </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">От материал</th>
                                        <th scope="col">Код (от)</th>
                                        <th scope="col">Получен материал</th>
                                        <th scope="col">Код (получен)</th>
                                        <th scope="col">Получено количество</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groundMaterials as $groundMaterial)
                                        <tr>
                                            <td>{{ $groundMaterial->from_material_name }}</td>
                                            <td>{{ $groundMaterial->from_material_code }}</td>
                                            <td>{{ $groundMaterial->to_material_name }}</td>
                                            <td>{{ $groundMaterial->to_material_code }}</td>
                                            <td>{{ $groundMaterial->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingFive">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false"
                        aria-controls="flush-collapseFive">
                        <div class="mb-3 text-decoration-underline">Изпрани материали</div>
                        <div>
                            <p class="mx-3 my-0">Общо количество:
                                <strong>{{ $washedMaterialsTotal[0]->quantity ?? 0 }}</strong>
                            </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">От материал</th>
                                        <th scope="col">Код (от)</th>
                                        <th scope="col">Получен материал</th>
                                        <th scope="col">Код (получен)</th>
                                        <th scope="col">Получено количество</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($washedMaterials as $washedMaterial)
                                        <tr>
                                            <td>{{ $washedMaterial->from_material_name }}</td>
                                            <td>{{ $washedMaterial->from_material_code }}</td>
                                            <td>{{ $washedMaterial->to_material_name }}</td>
                                            <td>{{ $washedMaterial->to_material_code }}</td>
                                            <td>{{ $washedMaterial->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingSix">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false"
                        aria-controls="flush-collapseSix">
                        <div class="mb-3 text-decoration-underline">Гранулирани материали</div>
                        <div>
                            <p class="mx-3 my-0">Общо количество:
                                <strong>{{ $granularMaterialsTotal[0]->quantity ?? 0 }}</strong>
                            </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">От материал</th>
                                        <th scope="col">Код (от)</th>
                                        <th scope="col">Получен материал</th>
                                        <th scope="col">Код (получен)</th>
                                        <th scope="col">Получено количество</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($granularMaterials as $granularMaterial)
                                        <tr>
                                            <td>{{ $granularMaterial->from_material_name }}</td>
                                            <td>{{ $granularMaterial->from_material_code }}</td>
                                            <td>{{ $granularMaterial->to_material_name }}</td>
                                            <td>{{ $granularMaterial->to_material_code }}</td>
                                            <td>{{ $granularMaterial->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingSeven">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false"
                        aria-controls="flush-collapseSeven">
                        <div class="mb-3 text-decoration-underline">Продадени материали</div>
                        <div>
                            <p class="mx-3 my-0">Общо количество:
                                <strong>{{ $soldMaterialsTotal[0]->quantity ?? 0 }}</strong>
                            </p>
                            <p class="mx-3 my-0">Обща цена: <strong>{{ $soldMaterialsTotal[0]->price ?? 0 }}</strong>
                            </p>
                            <p class="mx-3 my-0">Средна цена:
                                <strong>{{ round($soldMaterialsTotal[0]->avg_price, 2) ?? 0 }}</strong>
                            </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Материал</th>
                                        <th scope="col">Код</th>
                                        <th scope="col">Продаден на</th>
                                        <th scope="col">Продадено количество</th>
                                        <th scope="col">Средна цена</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($soldMaterials as $soldMaterial)
                                        <tr>
                                            <td>{{ $soldMaterial->name }}</td>
                                            <td>{{ $soldMaterial->code }}</td>
                                            <td>{{ $soldMaterial->sold_to }}</td>
                                            <td>{{ $soldMaterial->quantity }}</td>
                                            <td>{{ round($soldMaterial->avg_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingEight">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false"
                        aria-controls="flush-collapseEight">
                        <div class="mb-3 text-decoration-underline">Разходи</div>
                        <div>
                            <p class="mx-3 my-0">Обща цена: <strong>{{ $expensesTotal[0]->price ?? 0 }}</strong> </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseEight" class="accordion-collapse collapse" aria-labelledby="flush-headingEight">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Вид/Тип</th>
                                        <th scope="col">Цена</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->type }}</td>
                                            <td>{{ $expense->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingNine">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false"
                        aria-controls="flush-collapseNine">
                        <div class="mb-3 text-decoration-underline">Заплати</div>
                        <div>
                            <p class="mx-3 my-0">Обща цена: <strong>{{ $salariesTotal[0]->price ?? 0 }}</strong> </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseNine" class="accordion-collapse collapse" aria-labelledby="flush-headingNine">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Служител</th>
                                        <th scope="col">Сума</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salaries as $salary)
                                        <tr>
                                            <td>{{ $salary->name }}</td>
                                            <td>{{ $salary->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item border rounded mt-5">
                <h5 class="accordion-header" id="flush-headingTen">
                    <button
                        class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTen" aria-expanded="false"
                        aria-controls="flush-collapseTen">
                        <div class="mb-3 text-decoration-underline">Предплатени</div>
                        <div>
                            <p class="mx-3 my-0">Обща цена: <strong>{{ $prepaidTotal[0]->price ?? 0 }}</strong> </p>
                        </div>
                    </button>
                </h5>
                <div id="flush-collapseTen" class="accordion-collapse collapse" aria-labelledby="flush-headingTen">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Име на служител</th>
                                        <th scope="col">Сума</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prepaid as $pp)
                                        <tr>
                                            <td>{{ $pp->name }}</td>
                                            <td>{{ $pp->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $totalIncome = $soldMaterialsTotal[0]->price;
                $totalExpenses = $boughtMaterialsTotal[0]->price + $expensesTotal[0]->price + $salariesTotal[0]->price + $prepaidTotal[0]->price;
                $profit = $totalIncome - $totalExpenses;
            @endphp
            <div class="fs-4 mt-4">Общо приходи за периода: {{ $totalIncome ?? 0 }}</div>
            <div class="fs-4">Общо разходи за периода: {{ $totalExpenses ?? 0 }} </div>
            <div class="fs-4"> Печалба: <span
                    class="{{ $profit >= 0 ? 'text-success' : 'text-danger' }}">{{ $profit ?? 0 }}</span></div>
        </div>
    @endsection
