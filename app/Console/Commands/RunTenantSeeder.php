<?php

namespace App\Console\Commands;

use App\Models\Tenant;
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
                            {tenant_id : The ID of the tenant to run seeders for}
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
        $seederClass = $this->option('class') ?: 'DatabaseSeeder';
        
        // Load tenant from Master DB
        $tenant = Tenant::find($tenantId);
        
        if (!$tenant) {
            $this->error("Tenant with ID {$tenantId} not found in Master DB.");
            return 1;
        }

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

            $this->info("\n✓ Seeding completed for tenant: {$tenant->domain}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Error running seeders: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        } finally {
            // Switch back to Master DB
            $tenantDbService->switchToMaster();
        }
    }
}
