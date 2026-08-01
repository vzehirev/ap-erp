<?php

namespace App\Http\Controllers;

use App\Models\BoughtMaterial;
use App\Models\GranularMaterial;
use App\Models\GroundMaterial;
use App\Models\Material;
use App\Models\Partner;
use App\Models\SoldMaterial;
use App\Models\SortedMaterial;
use App\Models\WashedMaterial;
use App\Models\Worker;

/**
 * The six movement ledgers. In the original each of these had a matching
 * store* and delete* action; they are gone on this branch, along with their
 * routes and form requests. The add-material and delete forms still render,
 * because they are part of what the demo is showing - they simply have nothing
 * behind them.
 */
class MaterialsController extends Controller
{
    public function indexBoughtMaterials()
    {
        return view('materials.bought', [
            'partners' => Partner::orderBy('name')->get(),
            'materials' => Material::orderBy('name')->get(),
            'boughtMaterials' => BoughtMaterial::orderBy('bought_on', 'desc')
                ->orderBy('id', 'desc')
                ->with('material', 'partner')
                ->paginate(100),
        ]);
    }

    public function indexSortedMaterials()
    {
        return view('materials.sorted', [
            'materials' => Material::orderBy('name')->get(),
            'partners' => Partner::orderBy('name')->get(),
            'workers' => Worker::orderBy('name')->get(),
            'sortedMaterials' => SortedMaterial::orderBy('sorted_on', 'desc')
                ->orderBy('id', 'desc')
                ->with('workers', 'from_material', 'to_material')
                ->paginate(100),
        ]);
    }

    public function indexGroundMaterials()
    {
        return view('materials.ground', [
            'workers' => Worker::orderBy('name')->get(),
            'materials' => Material::orderBy('name')->get(),
            'groundMaterials' => GroundMaterial::orderBy('ground_on', 'desc')
                ->orderBy('id', 'desc')
                ->with('worker', 'from_material', 'to_material')
                ->paginate(100),
        ]);
    }

    public function indexWashedMaterials()
    {
        return view('materials.washed', [
            'workers' => Worker::orderBy('name')->get(),
            'materials' => Material::orderBy('name')->get(),
            'washedMaterials' => WashedMaterial::orderBy('washed_on', 'desc')
                ->orderBy('id', 'desc')
                ->with('worker', 'from_material', 'to_material')
                ->paginate(100),
        ]);
    }

    public function indexGranularMaterials()
    {
        return view('materials.granular', [
            'workers' => Worker::orderBy('name')->get(),
            'materials' => Material::orderBy('name')->get(),
            'granularMaterials' => GranularMaterial::orderBy('granular_on', 'desc')
                ->orderBy('id', 'desc')
                ->with('worker', 'from_materials', 'to_material')
                ->paginate(100),
        ]);
    }

    public function indexSoldMaterials()
    {
        return view('materials.sold', [
            'materials' => Material::orderBy('name')->get(),
            'partners' => Partner::orderBy('name')->get(),
            'soldMaterials' => SoldMaterial::orderBy('sold_on', 'desc')
                ->orderBy('id', 'desc')
                ->with('partner', 'material')
                ->paginate(100),
        ]);
    }
}
