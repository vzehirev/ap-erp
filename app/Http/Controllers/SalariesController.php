<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalaryRequest;
use App\Models\Salary;
use App\Models\Worker;

class SalariesController extends Controller
{
    function index()
    {
        $workers = Worker::orderBy('name', 'asc')->paginate(100);
        $salaries = Salary::orderBy('date', 'desc')->paginate(100);

        return view('salaries.index', ['salaries' => $salaries, 'workers' => $workers]);
    }

    function store(StoreSalaryRequest $request)
    {
        Salary::create($request->all());

        return back()->with('success', 'Успешно добавена заплата.');
    }
}
