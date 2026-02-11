<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'Manager',
            'Relationship Manager',
            'IB Manager',
            'Viewer',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                [
                    'name' => $roleName,
                    'guard_name' => 'web',
                ]
            );
        }
    }
}
