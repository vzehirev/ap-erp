<?php

namespace App\Http\Controllers;

use App\Models\Prepaid;
use App\Models\Worker;

class PrepaidController extends Controller
{
    public function index()
    {
        return view('prepaid.index', [
            'workers' => Worker::orderBy('name')->get(),
            'prepaid' => Prepaid::orderBy('paid_on', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(100),
        ]);
    }
}
