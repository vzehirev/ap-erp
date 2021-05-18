<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Home\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'getHome'])->middleware('auth');

Route::get('/register', [UsersController::class, 'getRegister'])->middleware('guest');
Route::post('/register', [UsersController::class, 'postRegister'])->middleware('guest');

Route::get('/login', [UsersController::class, 'getLogin'])->middleware('guest');
Route::post('/login', [UsersController::class, 'postLogin'])->middleware('guest');

Route::post('/logout', [UsersController::class, 'postLogout'])->middleware('auth');
