<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
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
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::apiResource('bookings', BookingController::class);
Route::patch('bookings/{id}/restore', [BookingController::class, 'restore']);
Route::resource('rooms', RoomController::class);

Route::resource('users', UserController::class);
Route::resource('favorites', FavoriteController::class);
