<?php
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PaymentController;

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
// Route::middleware('auth')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::apiResource('bookings', BookingController::class);
Route::patch('bookings/{id}/restore', [BookingController::class, 'restore']);
Route::post('bookings/search', [BookingController::class, 'search']);
Route::resource('rooms', RoomController::class);
Route::resource('users', UserController::class);
Route::resource('feedbacks', FeedbackController::class);
Route::resource('payments', PaymentController::class);
Route::namespace('api')->group(function(){
    Route::post('login', [AuthController::class,'login']);
    Route::post('signup', [AuthController::class,'signup']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('profile', [AuthController::class,'profile']);
});
// routes/api.php
Route::get('/user/{userId}/favorite-rooms', [FavoriteController::class, 'getFavoriteRooms']);


