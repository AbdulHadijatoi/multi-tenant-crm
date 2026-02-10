<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Tenant\AuthController;
// Legacy controllers - commented out as they don't exist
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\DashboardController;
// use App\Http\Controllers\Api\WalletController;
// use App\Http\Controllers\Api\ForexController;
// use App\Http\Controllers\Api\PropFirmController;
// use App\Http\Controllers\Api\InvestmentController;
// use App\Http\Controllers\Api\SupportController;

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
// tenant middleware handles: token validation, tenant switching, and user authentication
Route::group(['middleware' => ['tenant']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/profile', [AuthController::class, 'updateProfile']);
});



// Legacy routes - COMMENTED OUT: Controllers don't exist, use tenant.php routes instead
// Route::prefix('v1')->group(function () {
//     
//     // Public Routes (Legacy - consider removing)
//     Route::post('/auth/login', [AuthController::class, 'login']);
//     Route::post('/auth/register', [AuthController::class, 'register']);
//
//     // Protected Routes
//     Route::middleware('auth:sanctum')->group(function () {
//         
//         // User Profile
//         Route::get('/user', [AuthController::class, 'user']);
//         Route::post('/user/settings', [AuthController::class, 'updateSettings']);
//
//         // Dashboard Stats
//         Route::get('/dashboard/stats', [DashboardController::class, 'index']);
//
//         // Wallet Module
//         Route::get('/wallet', [WalletController::class, 'index']);
//         Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
//         Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
//         Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
//
//         // Forex Trading Module
//         Route::get('/forex/accounts', [ForexController::class, 'index']);
//         Route::post('/forex/accounts', [ForexController::class, 'store']);
//         Route::get('/forex/accounts/{id}/history', [ForexController::class, 'history']);
//
//         // Prop Firm Module
//         Route::get('/prop-firm/challenges', [PropFirmController::class, 'plans']);
//         Route::post('/prop-firm/purchase', [PropFirmController::class, 'purchase']);
//         Route::get('/prop-firm/my-challenges', [PropFirmController::class, 'myChallenges']);
//
//         // Investment Module
//         Route::get('/investments/plans', [InvestmentController::class, 'plans']);
//         Route::get('/investments/portfolio', [InvestmentController::class, 'portfolio']);
//         Route::post('/investments/invest', [InvestmentController::class, 'invest']);
//
//         // P2P & Copy Trading (Placeholders)
//         Route::get('/p2p/offers', [DashboardController::class, 'p2pOffers']);
//         Route::get('/copy-trading/traders', [DashboardController::class, 'traders']);
//
//         // Support
//         Route::get('/support/tickets', [SupportController::class, 'index']);
//         Route::post('/support/tickets', [SupportController::class, 'store']);
//     });
// });
