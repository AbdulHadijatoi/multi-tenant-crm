<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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