<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function setPasswordAttribute($password)
    {
        return $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    static function login($request)
    {
        $email = '';

        if (filter_var($request['username_or_email'], FILTER_VALIDATE_EMAIL)) {
            $email = $request['username_or_email'];
        } else {
            try {
                $email = User::where('username', $request['username_or_email'])->first()->email;
            } catch (Exception $ex) {
                Log::error($ex);
                return false;
            }
        }

        return auth()->attempt(
            [
                'email' => $email,
                'password' => $request['password'],
            ],
            $request['remember_me'] ?? false,
        );
    }
}
