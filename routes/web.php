<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Materials\MaterialController;
use App\Http\Controllers\Others\OthersController;

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

Route::middleware('guest')->group(function () {
    Route::get('/register', [UsersController::class, 'getRegister']);
    Route::post('/register', [UsersController::class, 'postRegister']);

    Route::get('/login', [UsersController::class, 'getLogin']);
    Route::post('/login', [UsersController::class, 'postLogin']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'getHome']);

    Route::post('/logout', [UsersController::class, 'postLogout']);

    Route::get('/bought-materials', [MaterialController::class, 'getBoughtMaterials']);
    Route::post('/bought-materials', [MaterialController::class, 'postBoughtMaterials']);

    Route::get('/others', [OthersController::class, 'getOthers']);
    Route::post('/add-partner', [OthersController::class, 'addPartner']);
    Route::post('/add-product', [OthersController::class, 'addProduct']);
});
