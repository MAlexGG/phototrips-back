<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/photos', [PhotoController::class, 'index']);
    Route::post('/photos', [PhotoController::class, 'store']);
    Route::get('/photos/{id}', [PhotoController::class, 'show']);
    Route::post('/photos/{id}', [PhotoController::class, 'update']);
    Route::delete('/photos/{id}', [PhotoController::class, 'destroy']);
    Route::get('/photos/city/{id}', [PhotoController::class, 'showByCity']);

    Route::get('/cities', [CityController::class, 'index']);
    Route::post('/cities', [CityController::class, 'store']);
    Route::get('/cities/{id}', [CityController::class, 'show']);
    Route::put('/cities/{id}', [CityController::class, 'update']);
    Route::delete('/cities/{id}', [CityController::class, 'destroy']);
    Route::get('/cities/country/{id}', [CityController::class, 'showByCountry']);

    Route::get('/countries', [CountryController::class, 'index']);
    Route::post('/countries', [CountryController::class, 'store']);
    Route::get('/countries/{id}', [CountryController::class, 'show']);
    Route::put('/countries/{id}', [CountryController::class, 'update']);
    Route::delete('/countries/{id}', [CountryController::class, 'destroy']);
    Route::get('/countries/continent/{id}', [CountryController::class, 'showByContinent']);

    Route::get('/validate/{id}', [AdminController::class, 'validateByAdmin']);
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::delete('/users/{id}', [AdminController::class, 'destroyUsers']);

    Route::post('/logout', [AuthController::class, 'logout']);
});