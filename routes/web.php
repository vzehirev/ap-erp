<?php

use App\Http\Controllers\ExpensesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\OthersController;
use App\Http\Controllers\PrepaidController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SalariesController;

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

    Route::get('/bought-materials', [MaterialsController::class, 'indexBoughtMaterials']);
    Route::post('/bought-materials', [MaterialsController::class, 'storeBoughtMaterial']);
    Route::post('/delete-bought-material/{bought_material}', [MaterialsController::class, 'deleteBoughtMaterial']);

    // Route::get('/wasted-materials', [MaterialsController::class, 'indexWastedMaterials']);
    // Route::post('/wasted-materials', [MaterialsController::class, 'storeWastedMaterial']);

    Route::get('/sorted-materials', [MaterialsController::class, 'indexSortedMaterials']);
    Route::post('/sorted-materials', [MaterialsController::class, 'storeSortedMaterial']);
    Route::post('/delete-sorted-material/{sorted_material}', [MaterialsController::class, 'deleteSortedMaterial']);

    Route::get('/ground-materials', [MaterialsController::class, 'indexGroundMaterials']);
    Route::post('/ground-materials', [MaterialsController::class, 'storeGroundMaterial']);
    Route::post('/delete-ground-material/{ground_material}', [MaterialsController::class, 'deleteGroundMaterial']);

    Route::get('/washed-materials', [MaterialsController::class, 'indexWashedMaterials']);
    Route::post('/washed-materials', [MaterialsController::class, 'storeWashedMaterial']);
    Route::post('/delete-washed-material/{washed_material}', [MaterialsController::class, 'deleteWashedMaterial']);

    Route::get('/granular-materials', [MaterialsController::class, 'indexGranularMaterials']);
    Route::post('/granular-materials', [MaterialsController::class, 'storeGranularMaterial']);
    Route::post('/delete-granular-material/{granular_material}', [MaterialsController::class, 'deleteGranularMaterial']);

    Route::get('/sold-materials', [MaterialsController::class, 'indexSoldMaterials']);
    Route::post('/sold-materials', [MaterialsController::class, 'storeSoldMaterial']);
    Route::post('/delete-sold-material/{sold_material}', [MaterialsController::class, 'deleteSoldMaterial']);

    Route::get('/expenses', [ExpensesController::class, 'index']);
    Route::post('/expenses', [ExpensesController::class, 'store']);
    Route::post('/delete-expense/{expense}', [ExpensesController::class, 'delete']);

    Route::get('/salaries', [SalariesController::class, 'index']);
    Route::post('/salaries', [SalariesController::class, 'store']);
    Route::post('/delete-salary/{salary}', [SalariesController::class, 'delete']);

    Route::get('/prepaid', [PrepaidController::class, 'index']);
    Route::post('/prepaid', [PrepaidController::class, 'store']);
    Route::post('/delete-prepaid/{prepaid}', [PrepaidController::class, 'delete']);

    Route::get('/reports', [ReportsController::class, 'index']);
    Route::post('/reports', [ReportsController::class, 'index']);
    Route::get('/available-materials', [ReportsController::class, 'indexAvailableMaterials']);

    Route::get('/others', [OthersController::class, 'index']);
    Route::post('/store-partner', [OthersController::class, 'storePartner']);
    Route::post('/store-material', [OthersController::class, 'storeMaterial']);
    Route::post('/store-worker', [OthersController::class, 'storeWorker']);
});
