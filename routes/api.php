<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccommodationController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\DetailController;
use App\Http\Controllers\Api\ItineraryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PackListController;
use App\Http\Controllers\Api\Payment_MethodController;
use App\Http\Controllers\Api\PaymentCardDetailsController;
use App\Http\Controllers\Api\PaymentGcashDetailsController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


// Core resources (registered within middleware group below)


Route::middleware('api')->group(function () {
    Route::apiResource('destinations', App\Http\Controllers\Api\DestinationController::class);
    Route::apiResource('trips', App\Http\Controllers\Api\TripController::class);
    Route::apiResource('accommodations', App\Http\Controllers\Api\AccommodationController::class);
    Route::apiResource('itineraries', App\Http\Controllers\Api\ItineraryController::class);
    Route::apiResource('pack-lists', App\Http\Controllers\Api\PackListController::class);
    Route::apiResource('bookings', App\Http\Controllers\Api\BookingController::class);
    Route::apiResource('details', App\Http\Controllers\Api\DetailController::class);
    Route::apiResource('notifications', App\Http\Controllers\Api\NotificationController::class);
    Route::apiResource('payment-methods', App\Http\Controllers\Api\Payment_MethodController::class);
    Route::apiResource('payment-card-details', App\Http\Controllers\Api\PaymentCardDetailsController::class);
    Route::apiResource('payment-gcash-details', App\Http\Controllers\Api\PaymentGcashDetailsController::class);
});

/*
Route::get('/destination', [DestinationController::class, 'index']);
Route::get('/destination/{destination}', [DestinationController::class, 'show']);
Route::post('/destination', [DestinationController::class, 'store']);
Route::put('/destination/{destination}', [DestinationController::class, 'update']);
Route::delete('/destination/{destination}', [DestinationController::class, 'destroy']);
*/





Route::middleware(['auth:sanctum'])->group(function () {
    // User routes
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('user.show');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');

    Route::get('/authenticated-user', [UserController::class, 'authenticated_user'])->name('user.authenticated');


});

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/signup', [AuthController::class, 'Signup'])->name('auth.signup');

