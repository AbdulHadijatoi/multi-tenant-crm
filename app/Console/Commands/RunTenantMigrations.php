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
                            {tenant_id? : The ID of the tenant to run migrations for (optional - runs on all tenants if not provided)}
                            {--fresh : Drop all tables and re-run all migrations}
                            {--seed : Indicates if the seed task should be re-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations on tenant database(s)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        
        // If tenant_id is provided, run on that tenant only
        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
            
            if (!$tenant) {
                $this->error("Tenant with ID {$tenantId} not found in Master DB.");
                return 1;
            }

            return $this->runMigrationsForTenant($tenant);
        }

        // If no tenant_id provided, run on all tenants
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->warn('No tenants found in Master DB.');
            return 0;
        }

        $this->info("Found {$tenants->count()} tenant(s). Running migrations on all tenants...\n");

        $successCount = 0;
        $failureCount = 0;

        foreach ($tenants as $tenant) {
            $this->newLine();
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $result = $this->runMigrationsForTenant($tenant);
            
            if ($result === 0) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        $this->newLine();
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("Summary: {$successCount} succeeded, {$failureCount} failed");

        return $failureCount > 0 ? 1 : 0;
    }

    /**
     * Run migrations for a specific tenant.
     *
     * @param Tenant $tenant
     * @return int
     */
    private function runMigrationsForTenant(Tenant $tenant): int
    {
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
                    $tenantDbService->switchToMaster();
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

            $this->info("✓ Migrations completed for tenant: {$tenant->domain}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Error running migrations for tenant {$tenant->domain}: " . $e->getMessage());
            return 1;
        } finally {
            // Switch back to Master DB
            $tenantDbService->switchToMaster();
        }
    }
}
