<?php

namespace App\Http\Controllers\Others;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPartnerRequest;
use App\Http\Requests\AddProductRequest;
use App\Models\Partner;
use App\Models\Product;

class OthersController extends Controller
{
    function getOthers()
    {
        return view('others.index');
    }

    function addPartner(AddPartnerRequest $request)
    {
        Partner::create($request->all());

        return back()->with('success', 'Успешно добавен партньор.');
    }

    function addProduct(AddProductRequest $request)
    {
        Product::create($request->all());

        return back()->with('success', 'Успешно добавен продукт.');
    }
}
