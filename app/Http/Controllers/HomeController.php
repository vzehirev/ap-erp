<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    /**
     * Not part of the original. The demo sets two cookies, so it says so
     * somewhere a visitor can read it.
     */
    public function privacy()
    {
        return view('home.privacy');
    }
}
