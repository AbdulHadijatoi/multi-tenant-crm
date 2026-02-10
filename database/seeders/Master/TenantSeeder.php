<?php

namespace Database\Seeders\Master;

use App\Models\Master\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create tenant record
        Tenant::firstOrCreate(
            ['domain' => 'client1.local'],
            [
                'db_name' => 'allinone_client_1',
                'db_username' => 'root',
                'db_password' => 'secret',
            ]
        );

        $this->command->info('Tenant created successfully!');
        $this->command->info('Domain: client1.local');
        $this->command->info('Database: allinone_client_1');
    }
}
