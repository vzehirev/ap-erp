<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    function index()
    {
        return view('users.index');
    }

    function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|confirmed',
            'username' => 'required|min:3|max:255',
            'password' => 'required|min:4|max:255|confirmed',
        ]);

        User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('home');
    }
}
