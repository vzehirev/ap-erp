<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostLoginRequest;
use App\Http\Requests\PostRegisterRequest;
use App\Models\User;


class UsersController extends Controller
{
    private $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    function getRegister()
    {
        return view('users.register');
    }

    function postRegister(PostRegisterRequest $request)
    {
        User::create($request->validated());

        return redirect('/login');
    }

    function getLogin()
    {
        return view('users.login');
    }

    function postLogin(PostLoginRequest $request)
    {
        if (!$this->user->loginUser($request->all())) {
            return back()->with('error', 'Неуспешен опит за вход в системата.');
        }

        return redirect()->intended();
    }

    function postLogout()
    {
        auth()->logout();

        return redirect('/');
    }
}
