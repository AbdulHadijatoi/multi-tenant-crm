<?php

namespace App\Http\Middleware;

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
        $tenantId = null;
        $tenant = null;

        // Try to get tenant from header first
        if ($request->hasHeader('X-Tenant-ID')) {
            $tenantId = $request->header('X-Tenant-ID');
        }
        // Try to get tenant from request parameter
        elseif ($request->has('tenant_id')) {
            $tenantId = $request->input('tenant_id');
        }
        // Try to get tenant from subdomain
        else {
            $host = $request->getHost();
            $parts = explode('.', $host);
            if (count($parts) > 2) {
                // Assuming subdomain format: tenant.example.com
                $tenantId = $parts[0];
            }
        }

        // If tenant ID found, create a simple tenant object
        // This can be enhanced later with a Tenant model
        if ($tenantId) {
            $tenant = (object) [
                'id' => (int) $tenantId,
                'identifier' => $tenantId,
            ];
        } else {
            // Default tenant if none specified (for development/testing)
            $tenant = (object) [
                'id' => 1,
                'identifier' => 'default',
            ];
        }

        // Make tenant available to controllers
        $request->merge(['tenant' => $tenant]);
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
