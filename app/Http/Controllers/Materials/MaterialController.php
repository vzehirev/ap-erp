<?php

namespace App\Http\Controllers\Materials;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuyMaterialRequest;
use App\Models\BoughtMaterial;
use App\Models\Partner;
use App\Models\Product;

class MaterialController extends Controller
{
    function index()
    {
        $partners = Partner::orderBy('name', 'asc')->get();
        $products = Product::orderBy('name', 'asc')->get();
        $boughtMaterials = BoughtMaterial::orderBy('bought_on', 'desc')->with('product', 'partner')->paginate(100);

        return view('materials.index', ['partners' => $partners, 'products' => $products, 'boughtMaterials' => $boughtMaterials]);
    }

    function store(BuyMaterialRequest $request)
    {
        BoughtMaterial::create($request->all());

        return redirect()->back()->with('success', 'Успешно добавен закупен материал.');
    }
}
