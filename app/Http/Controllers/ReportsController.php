<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    function index()
    {
        return view('reports.index');
    }

    function indexAvailableMaterials()
    {
        $availableMaterials = Material::orderBy('name', 'asc')->get();

        return view('reports.available_materials', ['availableMaterials' => $availableMaterials]);;
    }

    function indexMaterialsReports(Request $request)
    {
        $boughtMaterials = DB::table('bought_materials')
            ->join('materials', 'bought_materials.material_id', '=', 'materials.id')
            ->select(
                'materials.name',
                'materials.code',
                DB::raw('sum(bought_materials.quantity) as quantity'),
                DB::raw('sum(bought_materials.price) / sum(bought_materials.quantity) as avgPrice'),
            )
            ->groupBy('materials.name', 'materials.code')
            ->orderBy('materials.name', 'asc');

        $wastedMaterials = DB::table('wasted_materials')
            ->join('materials', 'wasted_materials.from_material_id', '=', 'materials.id')
            ->select(
                'materials.name',
                'materials.code',
                DB::raw('sum(wasted_materials.quantity) as quantity'),
            )
            ->groupBy('materials.name', 'materials.code')
            ->orderBy('materials.name', 'asc');

        $sortedMaterials = DB::table('sorted_materials')
            ->join('materials as from_materials', 'sorted_materials.from_material_id', '=', 'from_materials.id')
            ->join('materials as to_materials', 'sorted_materials.to_material_id', '=', 'to_materials.id')
            ->select(
                'from_materials.name as fromMaterial',
                'to_materials.name as toMaterial',
                DB::raw('sum(sorted_materials.quantity) as quantity'),
            )
            ->groupBy('fromMaterial', 'toMaterial')
            ->orderBy('toMaterial', 'asc');

        $groundMaterials = DB::table('ground_materials')
            ->join('materials as from_materials', 'ground_materials.from_material_id', '=', 'from_materials.id')
            ->join('materials as to_materials', 'ground_materials.to_material_id', '=', 'to_materials.id')
            ->select(
                'from_materials.name as fromMaterial',
                'to_materials.name as toMaterial',
                DB::raw('sum(ground_materials.quantity) as quantity'),
            )
            ->groupBy('fromMaterial', 'toMaterial')
            ->orderBy('toMaterial', 'asc');

        $washedMaterials = DB::table('washed_materials')
            ->join('materials as from_materials', 'washed_materials.from_material_id', '=', 'from_materials.id')
            ->join('materials as to_materials', 'washed_materials.to_material_id', '=', 'to_materials.id')
            ->select(
                'from_materials.name as fromMaterial',
                'to_materials.name as toMaterial',
                DB::raw('sum(washed_materials.quantity) as quantity'),
            )
            ->groupBy('fromMaterial', 'toMaterial')
            ->orderBy('toMaterial', 'asc');

        $granularMaterials = DB::table('granular_materials')
            ->join('materials as from_materials', 'granular_materials.from_material_id', '=', 'from_materials.id')
            ->join('materials as to_materials', 'granular_materials.to_material_id', '=', 'to_materials.id')
            ->select(
                'from_materials.name as fromMaterial',
                'to_materials.name as toMaterial',
                DB::raw('sum(granular_materials.quantity) as quantity'),
            )
            ->groupBy('fromMaterial', 'toMaterial')
            ->orderBy('toMaterial', 'asc');

        $soldMaterials = DB::table('sold_materials')
            ->join('materials', 'sold_materials.material_id', '=', 'materials.id')
            ->join('partners', 'sold_materials.partner_id', '=', 'partners.id')
            ->select(
                'materials.name',
                'materials.code',
                'partners.name as partnerName',
                DB::raw('sum(sold_materials.quantity) as quantity'),
                DB::raw('sum(sold_materials.price) / sum(sold_materials.quantity) as avgPrice')
            )
            ->groupBy('materials.name', 'materials.code', 'partners.name')
            ->orderBy('materials.name', 'asc');

        // if (strtotime($request->from_date) && strtotime($request->to_date)) {
        //     $query = $boughtMaterials->where([['bought_materials.bought_on', '>=', $request->from_date], ['bought_materials.bought_on', '<=', $request->to_date]]);
        // } else if (strtotime($request->from_date)) {
        //     $query = $boughtMaterials->where('bought_materials.bought_on', '>=', $request->from_date);
        // } else if (strtotime($request->to_date)) {
        //     $query = $boughtMaterials->where('bought_materials.bought_on', '<=', $request->to_date);
        // }

        return [
            'boughtMaterials' => $boughtMaterials->get(),
            'wastedMaterials' => $wastedMaterials->get(),
            'sortedMaterials' => $sortedMaterials->get(),
            'groundMaterials' => $groundMaterials->get(),
            'washedMaterials' => $washedMaterials->get(),
            'granularMaterials' => $granularMaterials->get(),
            'soldMaterials' => $soldMaterials->get(),
        ];
    }


    function indexStoredMaterials(Request $request)
    {
        $query = DB::table('granular_materials')->join('materials', 'granular_materials.material_id', '=', 'materials.id')
            ->select('materials.name', 'materials.code', DB::raw('sum(granular_materials.quantity) as quantity'))
            ->groupBy('materials.name', 'materials.code')
            ->orderBy('materials.name', 'asc');

        if (strtotime($request->from_date) && strtotime($request->to_date)) {
            $query = $query->where([['granular_materials.granular_on', '>=', $request->from_date], ['granular_materials.granular_on', '<=', $request->to_date]]);
        } else if (strtotime($request->from_date)) {
            $query = $query->where('granular_materials.granular_on', '>=', $request->from_date);
        } else if (strtotime($request->to_date)) {
            $query = $query->where('granular_materials.granular_on', '<=', $request->to_date);
        }

        return $query->get();
    }
}
