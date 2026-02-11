<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SuperAdmin\AuthController;

/*
|--------------------------------------------------------------------------
| SuperAdmin Routes (Master System)
|--------------------------------------------------------------------------
|
| Routes for superadmin/master system under /api/superadmin/v1 prefix
| Middleware: auth:sanctum, role:Super admin (for protected routes)
|
*/

// Public routes (no authentication required for login)
Route::group(['middleware' => []], function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
});

// Protected routes (authentication required)
// Use 'master' guard for superadmin to ensure master DB connection
Route::group(['middleware' => ['auth:master']], function () {
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
