<?php

namespace Database\Seeders;

use Database\Seeders\Master\MasterUserSeeder;
use Database\Seeders\Master\TenantSeeder;
use Database\Seeders\Master\TenantSubscriptionSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MasterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // switch to master connection
        Config::set('database.default', 'master');

        $this->call([
            TenantSeeder::class,
            MasterUserSeeder::class,
            TenantSubscriptionSeeder::class,
        ]);
    }
}
