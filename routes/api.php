<?php

use App\Http\Controllers\Api\CardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/photos', [CardController::class, 'index']);
Route::post('/photos', [CardController::class, 'store']);
Route::get('/photos/{id}', [CardController::class, 'show']);
Route::post('/photos/{id}', [CardController::class, 'update']);
Route::delete('/photos/{id}', [CardController::class, 'destroy']);
