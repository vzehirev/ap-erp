<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSoldMaterialRequest;
use App\Http\Requests\StoreBoughtMaterialRequest;
use App\Http\Requests\StoreGranularMaterialRequest;
use App\Http\Requests\StoreGroundMaterialRequest;
use App\Http\Requests\StoreSortedMaterialRequest;
use App\Http\Requests\StoreWashedMaterialRequest;
use App\Http\Requests\StoreWastedMaterialRequest;
use App\Models\BoughtMaterial;
use App\Models\GranularMaterial;
use App\Models\GroundMaterial;
use App\Models\Partner;
use App\Models\Material;
use App\Models\SoldMaterial;
use App\Models\SortedMaterial;
use App\Models\WashedMaterial;
use App\Models\WastedMaterial;
use App\Models\Worker;

class MaterialsController extends Controller
{
    function indexBoughtMaterials()
    {
        $partners = Partner::orderBy('name', 'asc')->get();
        $materials = Material::orderBy('name', 'asc')->get();
        $boughtMaterials = BoughtMaterial::orderBy('bought_on', 'desc')->with('material', 'partner')->paginate(100);

        return view('materials.bought', ['partners' => $partners, 'materials' => $materials, 'boughtMaterials' => $boughtMaterials]);
    }

    function storeBoughtMaterial(StoreBoughtMaterialRequest $request)
    {
        BoughtMaterial::create($request->validated());

        return redirect()->back()->with('success', 'Успешно добавен закупен материал.');
    }

    function indexWastedMaterials()
    {
        $materials = Material::orderBy('name', 'asc')->get();
        $workers = Worker::orderBy('name', 'asc')->get();
        $wastedMaterials = WastedMaterial::orderBy('wasted_on', 'desc')->with('from_material', 'worker')->paginate(100);

        return view('materials.wasted', ['materials' => $materials, 'workers' => $workers, 'wastedMaterials' => $wastedMaterials]);
    }

    function storeWastedMaterial(StoreWastedMaterialRequest $request)
    {
        $wastedMaterialType =
        WastedMaterial::create($request->validated());

        return redirect()->back()->with('success', 'Успешно добавен бракуван материал.');
    }

    function indexSortedMaterials()
    {
        $partners = Partner::orderBy('name', 'asc')->get();
        $workers = Worker::orderBy('name', 'asc')->get();
        $sortedMaterials = SortedMaterial::orderBy('sorted_on', 'desc')->with('worker', 'partner')->paginate(100);

        return view('materials.sorted', ['partners' => $partners, 'workers' => $workers, 'sortedMaterials' => $sortedMaterials]);
    }

    function storeSortedMaterial(StoreSortedMaterialRequest $request)
    {
        SortedMaterial::create($request->validated());

        return back()->with('success', 'Успешно добавен сортиран материал.');
    }

    function indexGroundMaterials()
    {
        $materials = Material::orderBy('name', 'asc')->get();
        $workers = Worker::orderBy('name', 'asc')->get();
        $groundMaterials = GroundMaterial::orderBy('ground_on', 'desc')->with('worker', 'material')->paginate(100);

        return view('materials.ground', ['workers' => $workers, 'materials' => $materials, 'groundMaterials' => $groundMaterials]);
    }

    function storeGroundMaterial(StoreGroundMaterialRequest $request)
    {
        GroundMaterial::create($request->validated());

        return back()->with('success', 'Успешно добавен смлян материал.');
    }

    function indexWashedMaterials()
    {
        $materials = Material::orderBy('name', 'asc')->get();
        $workers = Worker::orderBy('name', 'asc')->get();
        $washedMaterials = WashedMaterial::orderBy('washed_on', 'desc')->with('worker', 'from_material', 'to_material')->paginate(100);

        return view('materials.washed', ['workers' => $workers, 'materials' => $materials, 'washedMaterials' => $washedMaterials]);
    }

    function storeWashedMaterial(StoreWashedMaterialRequest $request)
    {
        WashedMaterial::create($request->validated());

        return back()->with('success', 'Успешно добавен изпран материал.');
    }

    function indexGranularMaterials()
    {
        $materials = Material::orderBy('name', 'asc')->get();
        $workers = Worker::orderBy('name', 'asc')->get();
        $granularMaterials = GranularMaterial::orderBy('granular_on', 'desc')->with('worker', 'material')->paginate(100);

        return view('materials.granular', ['workers' => $workers, 'materials' => $materials, 'granularMaterials' => $granularMaterials]);
    }

    function storeGranularMaterial(StoreGranularMaterialRequest $request)
    {
        GranularMaterial::create($request->validated());

        return back()->with('success', 'Успешно добавен гранулиран материал.');
    }
    function indexSoldMaterials()
    {
        $materials = Material::orderBy('name', 'asc')->get();
        $partners = Partner::orderBy('name', 'asc')->get();
        $soldMaterials = SoldMaterial::orderBy('sold_on', 'desc')->with('partner', 'material')->paginate(100);

        return view('materials.sold', ['partners' => $partners, 'materials' => $materials, 'soldMaterials' => $soldMaterials]);
    }

    function storeSoldMaterial(StoreSoldMaterialRequest $request)
    {
        SoldMaterial::create($request->validated());

        return back()->with('success', 'Успешно добавен продаден материал.');
    }
}
