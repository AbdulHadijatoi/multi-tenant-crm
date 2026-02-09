<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BridgeXController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Dashboard
Route::get('/', [BridgeXController::class, 'dashboard'])->name('dashboard');

// Financial Modules
Route::get('/wallet', [BridgeXController::class, 'wallet'])->name('wallet');
Route::get('/investments', [BridgeXController::class, 'investments'])->name('investments');
Route::get('/kyc', [BridgeXController::class, 'kyc'])->name('kyc');
Route::get('/loyalty', [BridgeXController::class, 'loyalty'])->name('loyalty');

// Trading Modules
Route::get('/forex', [BridgeXController::class, 'forex'])->name('forex');
Route::get('/prop-firm', [BridgeXController::class, 'propFirm'])->name('prop-firm');
Route::get('/p2p', [BridgeXController::class, 'p2p'])->name('p2p');
Route::get('/copy-trading', [BridgeXController::class, 'copyTrading'])->name('copy-trading');
Route::get('/download', [BridgeXController::class, 'download'])->name('download');

// Intelligence & Support
Route::get('/ai-center', [BridgeXController::class, 'aiCenter'])->name('ai-center');
Route::get('/widgets', [BridgeXController::class, 'widgets'])->name('widgets');
Route::get('/support', [BridgeXController::class, 'support'])->name('support');
Route::get('/profile', [BridgeXController::class, 'profile'])->name('profile');
Route::get('/settings', [BridgeXController::class, 'settings'])->name('settings');

// Action Routes (Examples)
Route::post('/wallet/deposit', [BridgeXController::class, 'processDeposit'])->name('wallet.deposit');
Route::post('/wallet/withdraw', [BridgeXController::class, 'processWithdraw'])->name('wallet.withdraw');
Route::post('/logout', [BridgeXController::class, 'logout'])->name('logout');