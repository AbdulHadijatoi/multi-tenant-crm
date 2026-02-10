<?php

namespace App\Console\Commands;

use App\Models\Master\Tenant;
use App\Services\TenantDatabaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RunTenantMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate 
                            {tenant_id : The ID of the tenant to run migrations for}
                            {--fresh : Drop all tables and re-run all migrations}
                            {--seed : Indicates if the seed task should be re-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations on a specific tenant database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        
        // Load tenant from Master DB
        $tenant = Tenant::find($tenantId);
        
        if (!$tenant) {
            $this->error("Tenant with ID {$tenantId} not found in Master DB.");
            return 1;
        }

        $this->info("Running migrations for tenant: {$tenant->domain} (ID: {$tenant->id})");
        $this->info("Database: {$tenant->db_name}");

        // Switch to tenant DB
        $tenantDbService = new TenantDatabaseService();
        $tenantDbService->switchToTenant($tenant);

        try {
            // Tenant migrations are in database/migrations/tenant folder
            $tenantMigrationsPath = 'database/migrations/tenant';

            if ($this->option('fresh')) {
                $this->warn('This will drop all tables in the tenant database!');
                if (!$this->confirm('Are you sure you want to continue?')) {
                    $this->info('Cancelled.');
                    return 0;
                }
                
                // Run fresh migrations from tenant folder
                Artisan::call('migrate:fresh', [
                    '--path' => $tenantMigrationsPath,
                    '--seed' => $this->option('seed'),
                ], $this->getOutput());
            } else {
                // Run pending migrations from tenant folder
                Artisan::call('migrate', [
                    '--path' => $tenantMigrationsPath,
                    '--seed' => $this->option('seed'),
                ], $this->getOutput());
            }

            $this->info("\n✓ Migrations completed for tenant: {$tenant->domain}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Error running migrations: " . $e->getMessage());
            return 1;
        } finally {
            // Switch back to Master DB
            $tenantDbService->switchToMaster();
        }
    }
}
