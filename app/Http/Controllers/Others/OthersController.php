<?php

namespace App\Http\Controllers\Others;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartnerRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreWorkerRequest;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Worker;

class OthersController extends Controller
{
    function index()
    {
        return view('others.index');
    }

    function storePartner(StorePartnerRequest $request)
    {
        Partner::create($request->all());

        return back()->with('success', 'Успешно добавен партньор.');
    }

    function storeProduct(StoreProductRequest $request)
    {
        Product::create($request->all());

        return back()->with('success', 'Успешно добавен продукт.');
    }

    function storeWorker(StoreWorkerRequest $request)
    {
        Worker::create($request->all());

        return back()->with('success', 'Успешно добавен служител.');
    }
}
