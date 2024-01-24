<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiEnterpriseController;
use App\Http\Controllers\Api\ApiNaceController;
use App\Http\Controllers\Api\ApiCodeController;
use App\Http\Controllers\Api\ApiSubsidyController;

use App\Http\Controllers\Api\BaseController;

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


Route::get('/', 
    [BaseController::class, 'index'])->name('api.index');

Route::get('/stats', 
     [BaseController::class, 'stats'])->name('api.stats');

Route::get('/lookup', 
    [BaseController::class, 'lookup'])->name('api.lookup');
 
Route::get('/enterprises/random/', 
    [ApiEnterpriseController::class, 'random'])->name('api.enterprises.random');

Route::get('/enterprises/{EnterpriseNumber}', 
    [ApiEnterpriseController::class, 'show'])->name('api.enterprises.show');

Route::get('/enterprises/{EnterpriseNumber}/digest', 
    [ApiEnterpriseController::class, 'showDigest'])->name('api.enterprises.showDigest');

Route::get('/codes/{category}/{code}/{language}', 
    [ApiCodeController::class, 'show'])->name('api.codes.show');

Route::get('/codes/{category}/{code}/{language}/insights', 
    [ApiCodeController::class, 'insights'])->name('api.codes.insights');

Route::get('/naces/{naceArray}', 
    [ApiNaceController::class, 'get'])->name('naces.get');

Route::get('/subsidies/{subsidies_category}', 
    [ApiSubsidyController::class, 'show'])->name('subsidies.show');
