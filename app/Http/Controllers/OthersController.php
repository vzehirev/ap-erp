<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartnerRequest;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\StoreWorkerRequest;
use App\Models\Partner;
use App\Models\Material;
use App\Models\Worker;

class OthersController extends Controller
{
    function index()
    {
        return view('others.index');
    }

    function storePartner(StorePartnerRequest $request)
    {
        Partner::create($request->validated());

        return back()->with('success', 'Успешно добавен партньор.');
    }

    function storeMaterial(StoreMaterialRequest $request)
    {
        Material::create($request->validated());

        return back()->with('success', 'Успешно добавен материал.');
    }

    function storeWorker(StoreWorkerRequest $request)
    {
        Worker::create($request->validated());

        return back()->with('success', 'Успешно добавен служител.');
    }
}
