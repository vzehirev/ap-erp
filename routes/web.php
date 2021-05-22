<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Materials\MaterialController;
use App\Http\Controllers\Others\OthersController;
use App\Http\Controllers\Sorting\SortingController;

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
    Route::get('/register', [UsersController::class, 'indexRegister']);
    Route::post('/register', [UsersController::class, 'storeRegister']);

    Route::get('/login', [UsersController::class, 'indexLogin']);
    Route::post('/login', [UsersController::class, 'storeLogin']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index']);

    Route::post('/logout', [UsersController::class, 'storeLogout']);

    Route::get('/bought-materials', [MaterialController::class, 'index']);
    Route::post('/bought-materials', [MaterialController::class, 'store']);

    Route::get('/others', [OthersController::class, 'index']);
    Route::post('/add-partner', [OthersController::class, 'storePartner']);
    Route::post('/add-product', [OthersController::class, 'storeProduct']);
    Route::post('/add-worker', [OthersController::class, 'storeWorker']);

    Route::get('/sorted-material', [SortingController::class, 'index']);
    Route::post('/sorted-material', [SortingController::class, 'store']);
});
