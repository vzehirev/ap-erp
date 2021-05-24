<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;


class UsersController extends Controller
{
    function indexRegister()
    {
        return view('users.register');
    }

    function storeRegister(StoreUserRequest $request)
    {
        User::create($request->validated());

        return redirect('/login');
    }

    function indexLogin()
    {
        return view('users.login');
    }

    function storeLogin(StoreLoginRequest $request)
    {
        if (User::login($request->all())) {
            return back()->with('error', 'Неуспешен опит за вход в системата.');
        }

        return redirect()->intended();
    }

    function storeLogout()
    {
        auth()->logout();

        return redirect('/');
    }
}
