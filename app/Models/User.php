<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * One row exists, and nothing ever signs in as it: DemoVisitor attaches it to
 * the request directly. The static login() helper the original kept here is
 * gone along with UsersController — there is no credential check left in this
 * branch to be got wrong.
 */
class User extends Authenticatable
{
    protected $fillable = [
        'email',
        'password',
        'username',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function setPasswordAttribute($password)
    {
        return $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }
}
