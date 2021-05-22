<?php

namespace App\Http\Controllers\Sorting;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSortedMaterialRequest;
use App\Models\Partner;
use App\Models\SortedMaterial;
use App\Models\Worker;

class SortingController extends Controller
{
    function index()
    {
        $partners = Partner::orderBy('name', 'asc')->get();
        $workers = Worker::orderBy('name', 'asc')->get();

        return view('sorting.index', ['partners' => $partners, 'workers' => $workers]);
    }

    function store(StoreSortedMaterialRequest $request)
    {
        SortedMaterial::create($request->all());

        return back()->with('success', 'Успешно добавен сортиран материал.');
    }
}
