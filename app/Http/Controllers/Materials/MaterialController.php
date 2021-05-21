<?php

namespace App\Http\Controllers\Materials;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuyMaterialRequest;
use App\Models\BoughtMaterial;
use App\Models\Partner;
use App\Models\Product;

class MaterialController extends Controller
{
    function getBoughtMaterials()
    {
        $partners = Partner::orderBy('name', 'asc')->get();
        $products = Product::orderBy('name', 'asc')->get();

        return view('materials.bought-materials', ['partners' => $partners, 'products' => $products]);
    }

    function postBoughtMaterials(BuyMaterialRequest $request)
    {
        BoughtMaterial::create($request->all());

        return redirect()->back()->with('success', 'Успешно добавен закупен материал.');
    }
}
