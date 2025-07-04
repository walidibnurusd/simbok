<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api.key')->group(function () {
    Route::prefix('master')->group(function () {
        Route::get('/docters', [APIController::class, 'docters']);

    });
});
  Route::get('/employee-data', [APIController::class, 'employeeData']);
