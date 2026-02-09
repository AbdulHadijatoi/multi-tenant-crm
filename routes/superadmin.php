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
Route::group(['middleware' => ['auth:sanctum', 'role:Super admin']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
