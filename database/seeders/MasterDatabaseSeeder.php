<?php

namespace Database\Seeders;

use Database\Seeders\Master\MasterUserSeeder;
use Database\Seeders\Master\TenantSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we're using the master connection
        DB::setDefaultConnection('master');

        $this->call([
            TenantSeeder::class,
            MasterUserSeeder::class,
        ]);
    }
}
