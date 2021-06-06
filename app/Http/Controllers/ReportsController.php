<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    function index(Request $request)
    {
        $data = $this->indexMaterialsReports($request);
        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        //dd($data);
        return view('reports.index', $data);
    }

    function indexAvailableMaterials()
    {
        $availableMaterials = Material::orderBy('name', 'asc')->get();

        return view('reports.available_materials', ['availableMaterials' => $availableMaterials]);;
    }

    function indexMaterialsReports(Request $request)
    {
        $queries = $this->getQueries();

        foreach ($queries as $key => $value) {
            if (str_contains($key, 'boughtMaterials')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'bought_materials', 'bought_on')->get();
            } else if (str_contains($key, 'wastedMaterials')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'wasted_materials', 'wasted_on')->get();
            } else if (str_contains($key, 'sortedMaterials')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'sorted_materials', 'sorted_on')->get();
            } else if (str_contains($key, 'groundMaterials')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'ground_materials', 'ground_on')->get();
            } else if (str_contains($key, 'washedMaterials')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'washed_materials', 'washed_on')->get();
            } else if (str_contains($key, 'granularMaterials')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'granular_materials', 'granular_on')->get();
            } else if (str_contains($key, 'soldMaterials')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'sold_materials', 'sold_on')->get();
            } else if (str_contains($key, 'salaries')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'salaries', 'date')->get();
            } else if (str_contains($key, 'prepaid')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'prepaid', 'paid_on')->get();
            } else if (str_contains($key, 'expenses')) {
                $queries[$key] = $this->setFromAndToDates($value, $request->from_date, $request->to_date, 'expenses', 'made_on')->get();
            }
        }

        return $queries;
    }

    private function getQueries()
    {
        return [
            'boughtMaterials' => DB::table('bought_materials')
                ->join('materials', 'bought_materials.material_id', '=', 'materials.id')
                ->select(
                    'materials.name',
                    'materials.code',
                    DB::raw('sum(bought_materials.quantity) as quantity'),
                    DB::raw('sum(bought_materials.price) / sum(bought_materials.quantity) as avg_price'),
                )
                ->groupBy('materials.name', 'materials.code')
                ->orderBy('materials.name', 'asc'),

            'wastedMaterials' => DB::table('wasted_materials')
                ->join('materials', 'materials.id', '=', 'wasted_materials.from_material_id')
                ->select(
                    'materials.name',
                    'materials.code',
                    DB::raw('sum(wasted_materials.quantity) as quantity'),
                )
                ->groupBy('materials.name', 'materials.code')
                ->orderBy('materials.name', 'asc'),

            'sortedMaterials' => DB::table('sorted_materials')
                ->join('materials as from_materials', 'sorted_materials.from_material_id', '=', 'from_materials.id')
                ->join('materials as to_materials', 'sorted_materials.to_material_id', '=', 'to_materials.id')
                ->select(
                    'from_materials.name as from_material_name',
                    'from_materials.code as from_material_code',
                    'to_materials.name as to_material_name',
                    'to_materials.code as to_material_code',
                    DB::raw('sum(sorted_materials.quantity) as quantity'),
                )
                ->groupBy('from_material_name', 'from_material_code', 'to_material_name', 'to_material_code')
                ->orderBy('to_material_name', 'asc'),

            'groundMaterials' => DB::table('ground_materials')
                ->join('materials as from_materials', 'ground_materials.from_material_id', '=', 'from_materials.id')
                ->join('materials as to_materials', 'ground_materials.to_material_id', '=', 'to_materials.id')
                ->select(
                    'from_materials.name as from_material_name',
                    'from_materials.code as from_material_code',
                    'to_materials.name as to_material_name',
                    'to_materials.code as to_material_code',
                    DB::raw('sum(ground_materials.quantity) as quantity'),
                )
                ->groupBy('from_material_name', 'from_material_code', 'to_material_name', 'to_material_code')
                ->orderBy('to_material_name', 'asc'),

            'washedMaterials' => DB::table('washed_materials')
                ->join('materials as from_materials', 'washed_materials.from_material_id', '=', 'from_materials.id')
                ->join('materials as to_materials', 'washed_materials.to_material_id', '=', 'to_materials.id')
                ->select(
                    'from_materials.name as from_material_name',
                    'from_materials.code as from_material_code',
                    'to_materials.name as to_material_name',
                    'to_materials.code as to_material_code',
                    DB::raw('sum(washed_materials.quantity) as quantity'),
                )
                ->groupBy('from_material_name', 'from_material_code', 'to_material_name', 'to_material_code')
                ->orderBy('to_material_name', 'asc'),

            'granularMaterials' => DB::table('granular_materials')
                ->join('materials as from_materials', 'granular_materials.from_material_id', '=', 'from_materials.id')
                ->join('materials as to_materials', 'granular_materials.to_material_id', '=', 'to_materials.id')
                ->select(
                    'from_materials.name as from_material_name',
                    'from_materials.code as from_material_code',
                    'to_materials.name as to_material_name',
                    'to_materials.code as to_material_code',
                    DB::raw('sum(granular_materials.quantity) as quantity'),
                )
                ->groupBy('from_material_name', 'from_material_code', 'to_material_name', 'to_material_code')
                ->orderBy('to_material_name', 'asc'),

            'soldMaterials' => DB::table('sold_materials')
                ->join('materials', 'sold_materials.material_id', '=', 'materials.id')
                ->join('partners', 'sold_materials.partner_id', '=', 'partners.id')
                ->select(
                    'materials.name',
                    'materials.code',
                    'partners.name as sold_to',
                    DB::raw('sum(sold_materials.quantity) as quantity'),
                    DB::raw('sum(sold_materials.price) / sum(sold_materials.quantity) as avg_price')
                )
                ->groupBy('materials.name', 'materials.code', 'sold_to')
                ->orderBy('materials.name', 'asc'),

            'boughtMaterialsTotal' => DB::table('bought_materials')
                ->select(
                    DB::raw('sum(bought_materials.price) as price'),
                    DB::raw('sum(bought_materials.quantity) as quantity'),
                    DB::raw('(sum(bought_materials.price) / sum(bought_materials.quantity)) as avg_price'),
                ),

            'wastedMaterialsTotal' => DB::table('wasted_materials')
                ->select(DB::raw('sum(wasted_materials.quantity) as quantity')),

            'sortedMaterialsTotal' => DB::table('sorted_materials')
                ->select(DB::raw('sum(sorted_materials.quantity) as quantity')),

            'groundMaterialsTotal' => DB::table('ground_materials')
                ->select(DB::raw('sum(ground_materials.quantity) as quantity')),

            'washedMaterialsTotal' => DB::table('washed_materials')
                ->select(DB::raw('sum(washed_materials.quantity) as quantity')),

            'granularMaterialsTotal' => DB::table('granular_materials')
                ->select(DB::raw('sum(granular_materials.quantity) as quantity')),

            'soldMaterialsTotal' => DB::table('sold_materials')
                ->select(
                    DB::raw('sum(sold_materials.price) as price'),
                    DB::raw('sum(sold_materials.quantity) as quantity'),
                    DB::raw('(sum(sold_materials.price) / sum(sold_materials.quantity)) as avg_price'),
                ),

            'expenses' => DB::table('expenses')
                ->select('type', DB::raw('sum(expenses.price) as price'))
                ->groupBy('expenses.type')
                ->orderBy('expenses.type', 'asc'),

            'expensesTotal' => DB::table('expenses')
                ->select(DB::raw('sum(expenses.price) as price')),

            'salaries' => DB::table('salaries')
                ->join('workers', 'salaries.worker_id', '=', 'workers.id')
                ->select(
                    'workers.name',
                    DB::raw('sum(salaries.price) as price'),
                )
                ->groupBy('workers.name')
                ->orderBy('workers.name', 'asc'),

            'salariesTotal' => DB::table('salaries')
                ->select(DB::raw('sum(salaries.price) as price')),

            'prepaid' => DB::table('prepaid')
                ->join('workers', 'prepaid.worker_id', '=', 'workers.id')
                ->select(
                    'workers.name',
                    DB::raw('sum(prepaid.price) as price'),
                )
                ->groupBy('workers.name')
                ->orderBy('workers.name', 'asc'),

            'prepaidTotal' => DB::table('prepaid')
                ->select(DB::raw('sum(prepaid.price) as price')),
        ];
    }

    private function setFromAndToDates($query, $from_date, $to_date, $table, $column)
    {
        if (strtotime($from_date) && strtotime($to_date)) {
            $query = $query->where([["{$table}.{$column}", '>=', $from_date], ["{$table}.{$column}", '<=', $to_date]]);
        } else if (strtotime($from_date)) {
            $query = $query->where("{$table}.{$column}", '>=', $from_date);
        } else if (strtotime($to_date)) {
            $query = $query->where("{$table}.{$column}", '<=', $to_date);
        }

        return $query;
    }
}
