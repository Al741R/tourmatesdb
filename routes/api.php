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


// Public routes - read-only access for browsing
Route::middleware('api')->group(function () {
    Route::get('destinations', [DestinationController::class, 'index']);
    Route::get('destinations/{destination}', [DestinationController::class, 'show']);
    Route::get('trips', [TripController::class, 'index']);
    Route::get('trips/{trip}', [TripController::class, 'show']);
    Route::get('accommodations', [AccommodationController::class, 'index']);
    Route::get('accommodations/{accommodation}', [AccommodationController::class, 'show']);
    Route::get('itineraries', [ItineraryController::class, 'index']);
    Route::get('itineraries/{itinerary}', [ItineraryController::class, 'show']);
});

// Protected routes - require authentication
Route::middleware(['auth:sanctum'])->group(function () {
    // Destinations - write operations
    Route::post('destinations', [DestinationController::class, 'store']);
    Route::put('destinations/{destination}', [DestinationController::class, 'update']);
    Route::patch('destinations/{destination}', [DestinationController::class, 'update']);
    Route::delete('destinations/{destination}', [DestinationController::class, 'destroy']);

    // Trips - write operations
    Route::post('trips', [TripController::class, 'store']);
    Route::put('trips/{trip}', [TripController::class, 'update']);
    Route::patch('trips/{trip}', [TripController::class, 'update']);
    Route::delete('trips/{trip}', [TripController::class, 'destroy']);

    // Accommodations - write operations
    Route::post('accommodations', [AccommodationController::class, 'store']);
    Route::put('accommodations/{accommodation}', [AccommodationController::class, 'update']);
    Route::patch('accommodations/{accommodation}', [AccommodationController::class, 'update']);
    Route::delete('accommodations/{accommodation}', [AccommodationController::class, 'destroy']);

    // Itineraries - write operations
    Route::post('itineraries', [ItineraryController::class, 'store']);
    Route::put('itineraries/{itinerary}', [ItineraryController::class, 'update']);
    Route::patch('itineraries/{itinerary}', [ItineraryController::class, 'update']);
    Route::delete('itineraries/{itinerary}', [ItineraryController::class, 'destroy']);

    // Pack Lists - all operations require auth
    Route::apiResource('pack-lists', App\Http\Controllers\Api\PackListController::class);

    // Bookings - sensitive user data, all operations require auth
    Route::apiResource('bookings', App\Http\Controllers\Api\BookingController::class);

    // Details - all operations require auth
    Route::apiResource('details', App\Http\Controllers\Api\DetailController::class);

    // Payment routes - sensitive financial data
    Route::apiResource('payment-methods', App\Http\Controllers\Api\Payment_MethodController::class);
    Route::apiResource('payment-card-details', App\Http\Controllers\Api\PaymentCardDetailsController::class);
    Route::apiResource('payment-gcash-details', App\Http\Controllers\Api\PaymentGcashDetailsController::class);

    // Notifications - user-specific
    Route::apiResource('notifications', App\Http\Controllers\Api\NotificationController::class);
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

// Authentication routes with rate limiting
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/signup', [AuthController::class, 'Signup'])->name('auth.signup');
});

