<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    function index()
    {
        return view('reports.index');
    }

    function indexWashedMaterials(Request $request)
    {
        $query = DB::table('washed_materials')->join('materials', 'washed_materials.to_material_id', '=', 'materials.id')
            ->select('materials.name', 'materials.code', DB::raw('sum(washed_materials.quantity) as quantity'))
            ->groupBy('materials.name', 'materials.code')
            ->orderBy('materials.name', 'asc');

        if (strtotime($request->from_date) && strtotime($request->to_date)) {
            $query = $query->where([['washed_materials.washed_on', '>=', $request->from_date], ['washed_materials.washed_on', '<=', $request->to_date]]);
        } else if (strtotime($request->from_date)) {
            $query = $query->where('washed_materials.washed_on', '>=', $request->from_date);
        } else if (strtotime($request->to_date)) {
            $query = $query->where('washed_materials.washed_on', '<=', $request->to_date);
        }

        return $query->get();
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
