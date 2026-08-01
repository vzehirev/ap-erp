<?php

use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\OthersController;
use App\Http\Controllers\PrepaidController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SalariesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web routes - read-only demo
|--------------------------------------------------------------------------
|
| The original registers 41 routes: 16 readable ones and 25 POSTs, 24 of which
| write. Every POST is gone from this branch, along with the guest group that
| held /login and /register. What is left is the fourteen pages a visitor can
| look at.
|
| Two changes from the original, both deliberate:
|
|   - /reports was a POST that only read. It is a GET here, so the demo can
|     enforce "GET and HEAD only" with no exceptions and a filtered report is a
|     shareable link.
|   - /privacy is new. The demo sets cookies, so it says so.
|
| There is no auth middleware because there is no authentication: DemoVisitor
| signs every request in as the seeded user before routing.
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/bought-materials', [MaterialsController::class, 'indexBoughtMaterials']);
Route::get('/sorted-materials', [MaterialsController::class, 'indexSortedMaterials']);
Route::get('/ground-materials', [MaterialsController::class, 'indexGroundMaterials']);
Route::get('/washed-materials', [MaterialsController::class, 'indexWashedMaterials']);
Route::get('/granular-materials', [MaterialsController::class, 'indexGranularMaterials']);
Route::get('/sold-materials', [MaterialsController::class, 'indexSoldMaterials']);

Route::get('/expenses', [ExpensesController::class, 'index']);
Route::get('/salaries', [SalariesController::class, 'index']);
Route::get('/prepaid', [PrepaidController::class, 'index']);

Route::get('/available-materials', [ReportsController::class, 'indexAvailableMaterials']);
Route::get('/reports', [ReportsController::class, 'index']);

Route::get('/others', [OthersController::class, 'index']);

Route::get('/privacy', [HomeController::class, 'privacy']);
