<?php

namespace App\Http\Middleware;

use App\Models\Master\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // X-Tenant-Domain header is required for all tenant APIs
        $domain = $request->header('X-Tenant-Domain');

        if (!$domain) {
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => __('messages.api.error_codes.MISSING_DOMAIN_HEADER'),
                'data' => null,
                'errors' => [],
                'error_code' => 'MISSING_DOMAIN_HEADER',
            ], 400);
        }

        // Load tenant from Master DB using domain from header
        $tenant = Tenant::where('domain', $domain)->first();

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => __('messages.api.error_codes.INVALID_TENANT'),
                'data' => null,
                'errors' => [],
                'error_code' => 'INVALID_TENANT',
            ], 401);
        }

        // Verify tenant.domain matches request header domain (security check)
        if ($tenant->domain !== $domain) {
            return response()->json([
                'success' => false,
                'failed' => true,
                'message' => __('messages.api.error_codes.DOMAIN_MISMATCH'),
                'data' => null,
                'errors' => [],
                'error_code' => 'DOMAIN_MISMATCH',
            ], 403);
        }

        // Make tenant available to controllers
        $request->merge(['tenant' => $tenant->toArray()]);
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
