<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\UserController;
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

// Route::group([

//     'middleware' => 'api',
//     'prefix' => 'auth'

// ], function ($router) {

//     Route::post('login', [AuthController::class,'login' ]);
//     Route::post('logout', 'AuthController@logout');

// });
Route::namespace('Api')->group(function(){

    Route::post('login', [AuthController::class,'login']);
    Route::post('signup', [AuthController::class,'signup']);

    Route::post('logout', [AuthController::class,'logout']);
    Route::post('profile', [AuthController::class,'profile']);

    Route::get('user', 'AuthController@user');

});

