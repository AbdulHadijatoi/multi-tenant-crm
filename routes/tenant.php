<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Tenant\AuthController;

/*
|--------------------------------------------------------------------------
| Tenant Routes (Client System)
|--------------------------------------------------------------------------
|
| Routes for tenant/client system under /api/v1 prefix
| Middleware: IdentifyTenant (for all routes)
| Additional middleware: auth:sanctum (for protected routes)
|
*/

// Public routes (no authentication required)
Route::group(['middleware' => []], function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
});

// Protected routes (authentication required)
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/profile', [AuthController::class, 'updateProfile']);
});
