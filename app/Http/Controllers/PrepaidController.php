<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrepaidRequest;
use App\Models\Prepaid;
use App\Models\Worker;

class PrepaidController extends Controller
{
    function index()
    {
        $workers = Worker::orderBy('name', 'asc')->get();
        $prepaid = Prepaid::orderBy('paid_on', 'desc')->paginate(100);

        return view('prepaid.index', ['workers' => $workers, 'prepaid' => $prepaid]);
    }

    function store(StorePrepaidRequest $request)
    {
        Prepaid::create($request->validated());

        return back()->with('success', 'Успешно добавена предплата.');
    }

    function delete(Prepaid $prepaid)
    {
        $prepaid->delete();

        return back()->with('success', 'Успешно изтрита предплата.');
    }
}
