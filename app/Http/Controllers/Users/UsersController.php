<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    function getRegister()
    {
        return view('users.register');
    }

    function postRegister(Request $request)
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

        return redirect('/login');
    }

    function getLogin()
    {
        return view('users.login');
    }

    function postLogin(Request $request)
    {
        $this->validate($request, [
            'usernameOrEmail' => 'required|min:3',
            'password' => 'required|min:4',
        ]);

        if (!$this->loginUser($request->usernameOrEmail, $request->password, $request->rememberMe)) {
            return back()->with('error', 'Неуспешен опит за вход в системата.');
        }

        return redirect()->intended();
    }

    function postLogout()
    {
        auth()->logout();

        return redirect('/');
    }

    private function loginUser($usernameOrEmail, $password, $rememberMe)
    {
        $email = '';

        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            $email = $usernameOrEmail;
        } else {
            try {
                $email = User::where('username', $usernameOrEmail)->first()->email;
            } catch (Exception $ex) {
                Log::error($ex);
                return false;
            }
        }

        return auth()->attempt(
            [
                'email' => $email,
                'password' => $password,
            ],
            $rememberMe
        );
    }
}
