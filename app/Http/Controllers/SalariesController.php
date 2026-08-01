<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Worker;

class SalariesController extends Controller
{
    public function index()
    {
        return view('salaries.index', [
            // The original paginated the workers as well, on the same ?page=
            // parameter as the salaries table, so the dropdown emptied itself
            // as soon as you moved to page 2. They are a form dropdown, not a
            // table - there is nothing to paginate.
            'workers' => Worker::orderBy('name')->get(),
            'salaries' => Salary::orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->with('worker')
                ->paginate(100),
        ]);
    }
}
