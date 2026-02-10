<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantDatabaseService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenantFromToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract bearer token from Authorization header
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => 'Unauthenticated',
                'data' => null,
                'errors' => [],
                'error_code' => 'UNAUTHORIZED',
            ], 401);
        }

        // Handle token prefix if configured (Sanctum can prefix tokens)
        $tokenPrefix = config('sanctum.token_prefix', '');
        if ($tokenPrefix && str_starts_with($bearerToken, $tokenPrefix)) {
            $bearerToken = substr($bearerToken, strlen($tokenPrefix));
        }

        // Hash the token to look it up in the database (Sanctum stores hashed tokens)
        $tokenHash = hash('sha256', $bearerToken);

        // Look up token in Master DB (PersonalAccessToken uses Master DB connection)
        $token = PersonalAccessToken::where('token', $tokenHash)->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => 'Invalid token',
                'data' => null,
                'errors' => [],
                'error_code' => 'INVALID_TOKEN',
            ], 401);
        }

        // Check if token is expired
        if ($token->expires_at && $token->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => 'Token expired',
                'data' => null,
                'errors' => [],
                'error_code' => 'TOKEN_EXPIRED',
            ], 401);
        }

        // Extract tenant_id and domain from token
        $tenantId = $token->tenant_id;
        $tokenDomain = $token->domain;

        if (!$tenantId || !$tokenDomain) {
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => 'Token missing tenant information',
                'data' => null,
                'errors' => [],
                'error_code' => 'INVALID_TOKEN',
            ], 401);
        }

        // Verify X-Tenant-Domain header matches token's domain
        $requestDomain = $request->header('X-Tenant-Domain');

        if (!$requestDomain || $requestDomain !== $tokenDomain) {
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => 'Domain mismatch',
                'data' => null,
                'errors' => [],
                'error_code' => 'DOMAIN_MISMATCH',
            ], 403);
        }

        // Load tenant from Master DB using tenant_id
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => 'Tenant not found',
                'data' => null,
                'errors' => [],
                'error_code' => 'TENANT_NOT_FOUND',
            ], 404);
        }

        // Use TenantDatabaseService to switch to Tenant DB
        $tenantDbService = new TenantDatabaseService();
        $tenantDbService->switchToTenant($tenant);

        // Load user from Tenant DB using tokenable_id from token
        $user = User::find($token->tokenable_id);

        if (!$user) {
            // Switch back to Master DB before returning error
            $tenantDbService->switchToMaster();
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => 'User not found',
                'data' => null,
                'errors' => [],
                'error_code' => 'USER_NOT_FOUND',
            ], 404);
        }

        // Verify tokenable_type matches User model
        if ($token->tokenable_type !== User::class) {
            $tenantDbService->switchToMaster();
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => 'Invalid token type',
                'data' => null,
                'errors' => [],
                'error_code' => 'INVALID_TOKEN',
            ], 401);
        }

        // Update token's last_used_at (need to switch back to Master DB temporarily)
        $tenantDbService->switchToMaster();
        $token->last_used_at = now();
        $token->save();
        $tenantDbService->switchToTenant($tenant);

        // Set the authenticated user on the request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // Store token and tenant in request attributes for controllers
        $request->attributes->set('tenant', $tenant);
        $request->attributes->set('token', $token);
        $request->merge(['tenant' => $tenant->toArray()]);

        // Continue to next middleware/controller
        return $next($request);
    }
}
