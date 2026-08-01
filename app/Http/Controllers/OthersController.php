<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Partner;
use App\Models\Worker;

/**
 * The reference-data screen. In the original this page was three buttons that
 * opened three "add" modals and nothing else - which reads as an empty page
 * once the buttons cannot do anything. The demo lists what those three modals
 * maintain, so the page shows the data as well as the way to create it.
 */
class OthersController extends Controller
{
    public function index()
    {
        return view('others.index', [
            'partners' => Partner::orderBy('name')->get(),
            'materials' => Material::orderBy('name')->get(),
            'workers' => Worker::orderBy('name')->get(),
        ]);
    }
}
