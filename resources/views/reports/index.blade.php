@extends('app')

@php
    /**
     * The original wrote this page out ten times: ten accordion items, each
     * with a hand-copied header, totals block and table. Every one of those
     * lines needed touching to be translated, so the repetition is expressed
     * as data instead. The rendered markup is the same.
     */
    $money = fn ($value) => number_format((float) $value, 2, '.', ' ');
    $kilos = fn ($value) => number_format((float) $value, 0, '.', ' ');

    $sections = [
        [
            'key' => 'bought',
            'title' => 'app.reports.bought',
            'rows' => $boughtMaterials,
            'totals' => [
                ['app.reports.total_quantity', $kilos($boughtMaterialsTotal[0]->quantity ?? 0)],
                ['app.reports.total_price', $money($boughtMaterialsTotal[0]->price ?? 0)],
                ['app.reports.average_price', $money($boughtMaterialsTotal[0]->avg_price ?? 0)],
            ],
            'columns' => [
                ['app.fields.material', 'name', null],
                ['app.fields.code', 'code', null],
                ['app.fields.bought_quantity', 'quantity', 'kilos'],
                ['app.reports.avg_price_column', 'avg_price', 'money'],
            ],
        ],
        [
            'key' => 'wasted',
            'title' => 'app.reports.wasted',
            'rows' => $wastedMaterials,
            'totals' => [
                ['app.reports.total_quantity', $kilos($wastedMaterialsTotal[0]->quantity ?? 0)],
            ],
            'columns' => [
                ['app.fields.material', 'name', null],
                ['app.fields.code', 'code', null],
                ['app.reports.wasted_quantity', 'quantity', 'kilos'],
            ],
        ],
        [
            'key' => 'sorted',
            'title' => 'app.reports.sorted',
            'rows' => $sortedMaterials,
            'totals' => [
                ['app.reports.total_quantity', $kilos($sortedMaterialsTotal[0]->quantity ?? 0)],
            ],
            'columns' => 'transformation',
        ],
        [
            'key' => 'ground',
            'title' => 'app.reports.ground',
            'rows' => $groundMaterials,
            'totals' => [
                ['app.reports.total_quantity', $kilos($groundMaterialsTotal[0]->quantity ?? 0)],
            ],
            'columns' => 'transformation',
        ],
        [
            'key' => 'washed',
            'title' => 'app.reports.washed',
            'rows' => $washedMaterials,
            'totals' => [
                ['app.reports.total_quantity', $kilos($washedMaterialsTotal[0]->quantity ?? 0)],
            ],
            'columns' => 'transformation',
        ],
        [
            'key' => 'granular',
            'title' => 'app.reports.granular',
            'rows' => $granularMaterials,
            'totals' => [
                ['app.reports.total_quantity', $kilos($granularMaterialsTotal[0]->quantity ?? 0)],
            ],
            'columns' => [
                ['app.reports.from_materials', 'from_materials_concat', null],
                ['app.fields.to_material', 'to_material_name', null],
                ['app.reports.to_material_code', 'to_material_code', null],
                ['app.fields.quantity_received', 'quantity', 'kilos'],
            ],
        ],
        [
            'key' => 'sold',
            'title' => 'app.reports.sold',
            'rows' => $soldMaterials,
            'totals' => [
                ['app.reports.total_quantity', $kilos($soldMaterialsTotal[0]->quantity ?? 0)],
                ['app.reports.total_price', $money($soldMaterialsTotal[0]->price ?? 0)],
                ['app.reports.average_price', $money($soldMaterialsTotal[0]->avg_price ?? 0)],
            ],
            'columns' => [
                ['app.fields.material', 'name', null],
                ['app.fields.code', 'code', null],
                ['app.fields.sold_to', 'sold_to', null],
                ['app.fields.sold_quantity', 'quantity', 'kilos'],
                ['app.reports.avg_price_column', 'avg_price', 'money'],
            ],
        ],
        [
            'key' => 'expenses',
            'title' => 'app.nav.expenses',
            'rows' => $expenses,
            'totals' => [
                ['app.reports.total_price', $money($expensesTotal[0]->price ?? 0)],
            ],
            'columns' => [
                ['app.reports.type', 'type', null],
                ['app.fields.price', 'price', 'money'],
            ],
        ],
        [
            'key' => 'salaries',
            'title' => 'app.nav.salaries',
            'rows' => $salaries,
            'totals' => [
                ['app.reports.total_price', $money($salariesTotal[0]->price ?? 0)],
            ],
            'columns' => [
                ['app.fields.worker', 'name', null],
                ['app.fields.amount', 'price', 'money'],
            ],
        ],
        [
            'key' => 'prepaid',
            'title' => 'app.nav.prepaid',
            'rows' => $prepaid,
            'totals' => [
                ['app.reports.total_price', $money($prepaidTotal[0]->price ?? 0)],
            ],
            'columns' => [
                ['app.fields.worker_name', 'name', null],
                ['app.fields.amount', 'price', 'money'],
            ],
        ],
    ];

    $transformationColumns = [
        ['app.fields.from_material', 'from_material_name', null],
        ['app.reports.from_material_code', 'from_material_code', null],
        ['app.fields.to_material', 'to_material_name', null],
        ['app.reports.to_material_code', 'to_material_code', null],
        ['app.fields.quantity_received', 'quantity', 'kilos'],
    ];
@endphp

@section('content')
    <div class="container mt-5 text-center">

        {{-- A GET form, not the original's POST. Nothing here writes, and this
             is what lets the demo refuse every non-GET request outright. It
             also makes a filtered report a link you can send to someone. --}}
        <form id="materials-reports-form" class="col-12 col-md-4 mx-auto" method="get" action="/reports">
            @if (app()->getLocale() !== 'bg')
                <input type="hidden" name="lang" value="{{ app()->getLocale() }}">
            @endif
            <div class="m-3">
                <label for="from_date" class="form-label">{{ __('app.reports.from_date') }}</label>
                {{-- Quoted. Unquoted, a space in the value introduces a new
                     attribute, and Blade's escaping does not escape spaces. --}}
                <input type="date" class="form-control" id="from_date" name="from_date"
                    value="{{ $from_date }}">
            </div>
            <div class="m-3">
                <label for="to_date" class="form-label">{{ __('app.reports.to_date') }}</label>
                <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $to_date }}">
            </div>
            <div class="m-3 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">{{ __('app.reports.apply') }}</button>
            </div>
        </form>

        <div class="m-3 d-flex justify-content-center">
            <a class="btn btn-outline-danger btn-sm" href="{{ durl('/reports') }}">{{ __('app.reports.clear') }}</a>
        </div>

        <div class="fs-5 text-primary">{{ __('app.reports.period') }}
            <strong>
                {{ $from_date ? \Carbon\Carbon::parse($from_date)->format('d-M-Y') : __('app.reports.from_start') }}
                &ndash;
                {{ $to_date ? \Carbon\Carbon::parse($to_date)->format('d-M-Y') : __('app.reports.until_today') }}
            </strong>
        </div>

        <div class="accordion accordion-flush" id="reportSections">
            @foreach ($sections as $section)
                @php
                    $columns = $section['columns'] === 'transformation' ? $transformationColumns : $section['columns'];
                @endphp
                <div class="accordion-item border rounded mt-5">
                    <h5 class="accordion-header" id="heading-{{ $section['key'] }}">
                        <button
                            class="flex-column align-items-center collapsed d-flex justify-content-center w-100 bg-light border-0 mx-auto p-2"
                            type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $section['key'] }}" aria-expanded="false"
                            aria-controls="collapse-{{ $section['key'] }}">
                            <div class="mb-3 text-decoration-underline">{{ __($section['title']) }}</div>
                            <div>
                                @foreach ($section['totals'] as [$label, $value])
                                    <p class="mx-3 my-0">{{ __($label) }} <strong>{{ $value }}</strong></p>
                                @endforeach
                            </div>
                        </button>
                    </h5>
                    <div id="collapse-{{ $section['key'] }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ $section['key'] }}">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            @foreach ($columns as [$label, $field, $format])
                                                <th scope="col">{{ __($label) }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($section['rows'] as $row)
                                            <tr>
                                                @foreach ($columns as [$label, $field, $format])
                                                    <td class="align-middle">
                                                        @if ($format === 'money')
                                                            {{ $money($row->{$field} ?? 0) }}
                                                        @elseif ($format === 'kilos')
                                                            {{ $kilos($row->{$field} ?? 0) }}
                                                        @else
                                                            {{ $row->{$field} }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="align-middle text-muted" colspan="{{ count($columns) }}">
                                                    {{ __('app.reference.no_rows') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @php
            $totalIncome = (float) ($soldMaterialsTotal[0]->price ?? 0);
            $totalExpenses = (float) ($boughtMaterialsTotal[0]->price ?? 0)
                + (float) ($expensesTotal[0]->price ?? 0)
                + (float) ($salariesTotal[0]->price ?? 0)
                + (float) ($prepaidTotal[0]->price ?? 0);
            $profit = $totalIncome - $totalExpenses;
        @endphp

        <div class="fs-4 mt-4">{{ __('app.reports.total_income') }} {{ $money($totalIncome) }}</div>
        <div class="fs-4">{{ __('app.reports.total_expenses') }} {{ $money($totalExpenses) }}</div>
        <div class="fs-4">{{ __('app.reports.profit') }}
            <span class="{{ $profit >= 0 ? 'text-success' : 'text-danger' }}">{{ $money($profit) }}</span>
        </div>

    </div>
@endsection
