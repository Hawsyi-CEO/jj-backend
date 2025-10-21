<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// Services routes
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/mc', [ServiceController::class, 'getMcServices']);
    Route::get('/wedding-organizer', [ServiceController::class, 'getWeddingServices']);
    Route::get('/{service}', [ServiceController::class, 'show']);
});

// Clients routes
Route::apiResource('clients', ClientController::class);

// Bookings routes
Route::apiResource('bookings', BookingController::class);
Route::get('bookings/status/{status}', [BookingController::class, 'getByStatus']);

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'JJ Portfolio API is running',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});