<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\ForexController;
use App\Http\Controllers\Api\PropFirmController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\SupportController;

/*
||--------------------------------------------------------------------------
|| API Routes
||--------------------------------------------------------------------------
||
|| Multi-tenant structure:
|| - Tenant routes: /api/v1 (Client system)
|| - SuperAdmin routes: /api/superadmin/v1 (Master system)
||
*/

// Tenant Routes (Client System)
// Prefix: /api/v1
// Middleware: IdentifyTenant (applied to all), auth:sanctum (for protected routes)
Route::prefix('v1')
    ->middleware(['api', 'IdentifyTenant'])
    ->group(base_path('routes/tenant.php'));

// SuperAdmin Routes (Master System)
// Prefix: /api/superadmin/v1
// Note: Login is public, other routes require auth:sanctum and role:Super admin
Route::prefix('superadmin/v1')
    ->middleware(['api'])
    ->group(base_path('routes/superadmin.php'));


    
// Legacy routes (keeping for backward compatibility - can be removed later)
Route::prefix('v1')->group(function () {
    
    // Public Routes (Legacy - consider removing)
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        
        // User Profile
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/user/settings', [AuthController::class, 'updateSettings']);

        // Dashboard Stats
        Route::get('/dashboard/stats', [DashboardController::class, 'index']);

        // Wallet Module
        Route::get('/wallet', [WalletController::class, 'index']);
        Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
        Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
        Route::get('/wallet/transactions', [WalletController::class, 'transactions']);

        // Forex Trading Module
        Route::get('/forex/accounts', [ForexController::class, 'index']);
        Route::post('/forex/accounts', [ForexController::class, 'store']);
        Route::get('/forex/accounts/{id}/history', [ForexController::class, 'history']);

        // Prop Firm Module
        Route::get('/prop-firm/challenges', [PropFirmController::class, 'plans']);
        Route::post('/prop-firm/purchase', [PropFirmController::class, 'purchase']);
        Route::get('/prop-firm/my-challenges', [PropFirmController::class, 'myChallenges']);

        // Investment Module
        Route::get('/investments/plans', [InvestmentController::class, 'plans']);
        Route::get('/investments/portfolio', [InvestmentController::class, 'portfolio']);
        Route::post('/investments/invest', [InvestmentController::class, 'invest']);

        // P2P & Copy Trading (Placeholders)
        Route::get('/p2p/offers', [DashboardController::class, 'p2pOffers']);
        Route::get('/copy-trading/traders', [DashboardController::class, 'traders']);

        // Support
        Route::get('/support/tickets', [SupportController::class, 'index']);
        Route::post('/support/tickets', [SupportController::class, 'store']);
    });
});
