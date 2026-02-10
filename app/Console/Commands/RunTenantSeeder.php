<?php

namespace App\Console\Commands;

use App\Models\Master\Tenant;
use App\Services\TenantDatabaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunTenantSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:seed 
                            {tenant_id? : The ID of the tenant to run seeders for (optional - runs on all tenants if not provided)}
                            {--class= : The seeder class to run (default: DatabaseSeeder)}
                            {--fresh : Run fresh migrations before seeding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run seeders on a specific tenant database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->argument('tenant_id');

        // Determine which seeder class to run
        $seederClass = $this->option('class') ?: 'DatabaseSeeder';

        // If tenant_id is provided, run on that tenant only
        if ($tenantId) {
            $tenant = Tenant::find($tenantId);

            if (! $tenant) {
                $this->error("Tenant with ID {$tenantId} not found in Master DB.");

                return 1;
            }

            return $this->runSeederForTenant($tenant, $seederClass);
        }

        // If no tenant_id provided, run on all tenants
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->warn('No tenants found in Master DB.');

            return 0;
        }

        $this->info("Found {$tenants->count()} tenant(s). Running seeders on all tenants...\n");

        $successCount = 0;
        $failureCount = 0;

        foreach ($tenants as $tenant) {
            $this->newLine();
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $result = $this->runSeederForTenant($tenant, $seederClass);

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
     * Run seeders for a specific tenant.
     *
     * @param  \App\Models\Master\Tenant  $tenant
     * @param  string  $seederClass
     * @return int
     */
    private function runSeederForTenant(Tenant $tenant, string $seederClass): int
    {
        $this->info("Running seeders for tenant: {$tenant->domain} (ID: {$tenant->id})");
        $this->info("Database: {$tenant->db_name}");
        $this->info("Seeder: {$seederClass}");

        // Switch to tenant DB
        $tenantDbService = new TenantDatabaseService();
        $tenantDbService->switchToTenant($tenant);

        try {
            // If --fresh option is provided, run fresh migrations first
            if ($this->option('fresh')) {
                $this->warn('Running fresh migrations before seeding...');
                $tenantMigrationsPath = 'database/migrations/tenant';
                Artisan::call('migrate:fresh', [
                    '--path' => $tenantMigrationsPath,
                ], $this->getOutput());
            }

            // Run the seeder
            Artisan::call('db:seed', [
                '--class' => $seederClass,
            ], $this->getOutput());

            $this->info("✓ Seeding completed for tenant: {$tenant->domain}");

            return 0;
        } catch (\Exception $e) {
            $this->error("Error running seeders for tenant {$tenant->domain}: " . $e->getMessage());

            return 1;
        } finally {
            // Switch back to Master DB
            $tenantDbService->switchToMaster();
        }
    }
}
