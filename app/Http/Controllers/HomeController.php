<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    function index()
    {
        return view('home.index');
    }
}
