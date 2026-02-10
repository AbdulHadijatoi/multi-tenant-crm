<?php

namespace App\Services;

use App\Models\Master\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantDatabaseService
{
    /**
     * Switch to tenant database connection.
     *
     * @param Tenant $tenant
     * @return void
     */
    public function switchToTenant(Tenant $tenant): void
    {
        // Get the master connection name from env (original default)
        $masterConnection = env('DB_CONNECTION', 'mysql');
        
        // Get the master connection config as base
        $masterConfig = config('database.connections.' . $masterConnection);
        
        // Create tenant connection config using tenant's database credentials
        $tenantConfig = [
            'driver' => $masterConfig['driver'] ?? 'mysql',
            'host' => $masterConfig['host'] ?? '127.0.0.1',
            'port' => $masterConfig['port'] ?? '3306',
            'database' => $tenant->db_name,
            'username' => $tenant->db_username,
            'password' => $tenant->db_password,
            'charset' => $masterConfig['charset'] ?? 'utf8mb4',
            'collation' => $masterConfig['collation'] ?? 'utf8mb4_unicode_ci',
            'prefix' => $masterConfig['prefix'] ?? '',
            'prefix_indexes' => $masterConfig['prefix_indexes'] ?? true,
            'strict' => $masterConfig['strict'] ?? true,
            'engine' => $masterConfig['engine'] ?? null,
            'options' => $masterConfig['options'] ?? [],
        ];

        // Set the tenant connection
        Config::set('database.connections.tenant', $tenantConfig);
        
        // Purge the connection to force reconnection
        DB::purge('tenant');
        
        // Set tenant as default connection
        Config::set('database.default', 'tenant');
        DB::setDefaultConnection('tenant');
    }

    /**
     * Switch back to master database connection.
     *
     * @return void
     */
    public function switchToMaster(): void
    {
        // Get the original default connection name from config
        $masterConnection = env('DB_CONNECTION', 'mysql');
        
        // Set master as default connection
        Config::set('database.default', $masterConnection);
        DB::setDefaultConnection($masterConnection);
    }
}
