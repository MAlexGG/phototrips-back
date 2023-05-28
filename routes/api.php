<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\PhotoController;
use App\Models\Photo;
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
    Route::delete('/photos/{id}', [PhotoController::class, 'destroy']);

    Route::get('/cities', [CityController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);
});