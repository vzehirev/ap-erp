<?php

namespace App\Http\Controllers;

use App\Models\Material;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * The aggregate report, and the stock list.
 *
 * Three changes from the original, all of them forced by publishing this page:
 *
 *   1. The filter was a POST. It only ever read, so it is a GET here - which is
 *      what lets the demo refuse every non-GET request without an exception,
 *      and makes a filtered report a link you can share.
 *   2. The two dates went into the query builder, and into Carbon::parse(),
 *      exactly as they arrived. `?from_date=garbage` was a 500 and
 *      `?from_date=2021` silently matched every row, because a partial date
 *      still compares as a string. They are parsed strictly now, and anything
 *      that is not a real Y-m-d date is treated as absent.
 *   3. The granular section summed with `sum(DISTINCT quantity)` to undo the
 *      row multiplication caused by joining the source-materials pivot. That
 *      collapses two batches that happen to weigh the same into one, so the
 *      total was quietly short. The pivot is out of the aggregate now.
 */
class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $from = $this->parseDate($request->query('from_date'));
        $to = $this->parseDate($request->query('to_date'));

        return view('reports.index', $this->reports($from, $to) + [
            'from_date' => $from,
            'to_date' => $to,
        ]);
    }

    public function indexAvailableMaterials()
    {
        return view('reports.available_materials', [
            'availableMaterials' => Material::orderBy('name')->get(),
        ]);
    }

    /**
     * Strict Y-m-d only. Anything else - a partial date, a word, an array from
     * `?from_date[]=x` - is treated as "no filter" rather than passed on.
     */
    private function parseDate(mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        if ($date === false) {
            return null;
        }

        $errors = DateTimeImmutable::getLastErrors();

        if ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            return null;
        }

        return $date->format('Y-m-d');
    }

    /**
     * @return array<string, \Illuminate\Support\Collection>
     */
    private function reports(?string $from, ?string $to): array
    {
        $between = function ($query, string $column) use ($from, $to) {
            if ($from !== null) {
                $query->where($column, '>=', $from);
            }

            if ($to !== null) {
                $query->where($column, '<=', $to);
            }

            return $query;
        };

        // A transformation ledger: N kilograms of one material go in, fewer come
        // out as another, and the difference is booked as waste. All four of
        // these read the same way.
        $transformation = function (string $table, string $dateColumn) use ($between) {
            return $between(
                DB::table($table)
                    ->join("materials as from_materials", "{$table}.from_material_id", '=', 'from_materials.id')
                    ->join("materials as to_materials", "{$table}.to_material_id", '=', 'to_materials.id')
                    ->select(
                        'from_materials.name as from_material_name',
                        'from_materials.code as from_material_code',
                        'to_materials.name as to_material_name',
                        'to_materials.code as to_material_code',
                        DB::raw("sum({$table}.quantity) as quantity"),
                    )
                    ->groupBy('from_materials.name', 'from_materials.code', 'to_materials.name', 'to_materials.code')
                    ->orderBy('to_materials.name'),
                "{$table}.{$dateColumn}"
            )->get();
        };

        $sumOf = function (string $table, string $dateColumn, string $column = 'quantity') use ($between) {
            return $between(
                DB::table($table)->select(DB::raw("coalesce(sum({$table}.{$column}), 0) as {$column}")),
                "{$table}.{$dateColumn}"
            )->get();
        };

        $tradeTotal = function (string $table, string $dateColumn) use ($between) {
            return $between(
                DB::table($table)->select(
                    DB::raw("coalesce(sum({$table}.price), 0) as price"),
                    DB::raw("coalesce(sum({$table}.quantity), 0) as quantity"),
                    DB::raw("coalesce(sum({$table}.price) / nullif(sum({$table}.quantity), 0), 0) as avg_price"),
                ),
                "{$table}.{$dateColumn}"
            )->get();
        };

        return [
            'boughtMaterials' => $between(
                DB::table('bought_materials')
                    ->join('materials', 'bought_materials.material_id', '=', 'materials.id')
                    ->select(
                        'materials.name',
                        'materials.code',
                        DB::raw('sum(bought_materials.quantity) as quantity'),
                        DB::raw('sum(bought_materials.price) / nullif(sum(bought_materials.quantity), 0) as avg_price'),
                    )
                    ->groupBy('materials.name', 'materials.code')
                    ->orderBy('materials.name'),
                'bought_materials.bought_on'
            )->get(),

            'wastedMaterials' => $between(
                DB::table('wasted_materials')
                    ->join('materials', 'materials.id', '=', 'wasted_materials.from_material_id')
                    ->select(
                        'materials.name',
                        'materials.code',
                        DB::raw('sum(wasted_materials.quantity) as quantity'),
                    )
                    ->groupBy('materials.name', 'materials.code')
                    ->orderBy('materials.name'),
                'wasted_materials.wasted_on'
            )->get(),

            'sortedMaterials' => $transformation('sorted_materials', 'sorted_on'),
            'groundMaterials' => $transformation('ground_materials', 'ground_on'),
            'washedMaterials' => $transformation('washed_materials', 'washed_on'),

            // Granulating takes several source materials at once, so the source
            // names come from a subquery rather than a join. Joining the pivot
            // into the aggregate is what produced the duplicate rows the
            // original then tried to undo with sum(DISTINCT ...).
            'granularMaterials' => $between(
                DB::table('granular_materials')
                    ->join('materials as to_materials', 'granular_materials.to_material_id', '=', 'to_materials.id')
                    ->select(
                        'to_materials.name as to_material_name',
                        'to_materials.code as to_material_code',
                        DB::raw('sum(granular_materials.quantity) as quantity'),
                        DB::raw('(select group_concat(distinct sources.name)
                                    from granular_material_from_material as pivot
                                    join materials as sources on sources.id = pivot.from_material_id
                                    join granular_materials as batches on batches.id = pivot.granular_material_id
                                   where batches.to_material_id = granular_materials.to_material_id
                                 ) as from_materials_concat'),
                    )
                    ->groupBy('to_materials.name', 'to_materials.code', 'granular_materials.to_material_id')
                    ->orderBy('to_materials.name'),
                'granular_materials.granular_on'
            )->get(),

            'soldMaterials' => $between(
                DB::table('sold_materials')
                    ->join('materials', 'sold_materials.material_id', '=', 'materials.id')
                    ->join('partners', 'sold_materials.partner_id', '=', 'partners.id')
                    ->select(
                        'materials.name',
                        'materials.code',
                        'partners.name as sold_to',
                        DB::raw('sum(sold_materials.quantity) as quantity'),
                        DB::raw('sum(sold_materials.price) / nullif(sum(sold_materials.quantity), 0) as avg_price'),
                    )
                    ->groupBy('materials.name', 'materials.code', 'partners.name')
                    ->orderBy('materials.name'),
                'sold_materials.sold_on'
            )->get(),

            'boughtMaterialsTotal' => $tradeTotal('bought_materials', 'bought_on'),
            'soldMaterialsTotal' => $tradeTotal('sold_materials', 'sold_on'),

            'wastedMaterialsTotal' => $sumOf('wasted_materials', 'wasted_on'),
            'sortedMaterialsTotal' => $sumOf('sorted_materials', 'sorted_on'),
            'groundMaterialsTotal' => $sumOf('ground_materials', 'ground_on'),
            'washedMaterialsTotal' => $sumOf('washed_materials', 'washed_on'),
            'granularMaterialsTotal' => $sumOf('granular_materials', 'granular_on'),

            'expenses' => $between(
                DB::table('expenses')
                    ->select('type', DB::raw('sum(expenses.price) as price'))
                    ->groupBy('expenses.type')
                    ->orderBy('expenses.type'),
                'expenses.made_on'
            )->get(),

            'expensesTotal' => $sumOf('expenses', 'made_on', 'price'),

            'salaries' => $between(
                DB::table('salaries')
                    ->join('workers', 'salaries.worker_id', '=', 'workers.id')
                    ->select('workers.name', DB::raw('sum(salaries.price) as price'))
                    ->groupBy('workers.name')
                    ->orderBy('workers.name'),
                'salaries.date'
            )->get(),

            'salariesTotal' => $sumOf('salaries', 'date', 'price'),

            'prepaid' => $between(
                DB::table('prepaid')
                    ->join('workers', 'prepaid.worker_id', '=', 'workers.id')
                    ->select('workers.name', DB::raw('sum(prepaid.price) as price'))
                    ->groupBy('workers.name')
                    ->orderBy('workers.name'),
                'prepaid.paid_on'
            )->get(),

            'prepaidTotal' => $sumOf('prepaid', 'paid_on', 'price'),
        ];
    }
}
