<?php

namespace Database\Seeders\Master;

use App\Models\Master\License;
use App\Models\Master\Plan;
use App\Models\Master\Subscription;
use App\Models\Master\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenantSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all tenants from master DB
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->command->warn('No tenants found in master database. Please create tenants first.');
            return;
        }

        // Create or get default plans
        $plans = $this->createPlans();

        // Get the first plan (or you can assign different plans to different tenants)
        $defaultPlan = $plans->first();

        $this->command->info("Found {$tenants->count()} tenant(s). Creating subscriptions and licenses...");

        foreach ($tenants as $tenant) {
            $this->command->info("Processing tenant: {$tenant->domain} (ID: {$tenant->id})");

            // Check if tenant already has a subscription
            $existingSubscription = Subscription::where('tenant_id', $tenant->id)->first();

            if ($existingSubscription) {
                $this->command->warn("  Tenant already has subscription (ID: {$existingSubscription->id}). Skipping...");
                continue;
            }

            // Create subscription for tenant
            $subscription = Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $defaultPlan->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addYear(), // Subscription valid for 1 year
                'billing_cycle' => 'yearly',
                'auto_renew' => true,
                'provider' => 'manual',
            ]);

            $this->command->info("  ✓ Created subscription (ID: {$subscription->id})");

            // Generate unique license key for this tenant
            $licenseKey = $this->generateLicenseKey($tenant);

            // Create license for tenant
            $license = License::create([
                'tenant_id' => $tenant->id,
                'subscription_id' => $subscription->id,
                'license_key' => $licenseKey,
                'status' => 'active',
                'issued_at' => now(),
                'expires_at' => now()->addYear(), // License valid for 1 year
            ]);

            $this->command->info("  ✓ Created license (ID: {$license->id})");
            $this->command->info("  License Key: {$licenseKey}");
        }

        $this->command->info("\n✓ Successfully created subscriptions and licenses for all tenants!");
    }

    /**
     * Create default plans if they don't exist.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function createPlans()
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'price' => 49.00,
                'billing_cycle' => 'monthly',
                'max_users' => 10,
                'max_storage' => 1073741824, // 1GB in bytes
                'features' => [
                    'basic_support' => true,
                    'email_support' => true,
                ],
            ],
            [
                'name' => 'Standard Plan',
                'price' => 99.00,
                'billing_cycle' => 'monthly',
                'max_users' => 50,
                'max_storage' => 10737418240, // 10GB in bytes
                'features' => [
                    'priority_support' => true,
                    'email_support' => true,
                    'phone_support' => true,
                ],
            ],
            [
                'name' => 'Premium Plan',
                'price' => 199.00,
                'billing_cycle' => 'monthly',
                'max_users' => 200,
                'max_storage' => 107374182400, // 100GB in bytes
                'features' => [
                    'priority_support' => true,
                    'email_support' => true,
                    'phone_support' => true,
                    'dedicated_manager' => true,
                ],
            ],
        ];

        $createdPlans = collect();

        foreach ($plans as $planData) {
            $plan = Plan::firstOrCreate(
                ['name' => $planData['name']],
                $planData
            );
            $createdPlans->push($plan);
        }

        $this->command->info("Created/verified {$createdPlans->count()} plan(s).");

        return $createdPlans;
    }

    /**
     * Generate a unique license key for a tenant.
     *
     * @param Tenant $tenant
     * @return string
     */
    private function generateLicenseKey(Tenant $tenant): string
    {
        $maxAttempts = 10;
        $attempt = 0;

        do {
            // Generate license key: LIC-{TENANT_ID}-{RANDOM_STRING}
            $randomString = strtoupper(Str::random(12));
            $licenseKey = sprintf('LIC-%03d-%s', $tenant->id, $randomString);

            // Check if license key already exists
            $exists = License::where('license_key', $licenseKey)->exists();
            $attempt++;

            if ($attempt >= $maxAttempts) {
                // Fallback: use timestamp if we can't generate unique key
                $licenseKey = sprintf('LIC-%03d-%s', $tenant->id, strtoupper(Str::random(16)));
                break;
            }
        } while ($exists);

        return $licenseKey;
    }
}
