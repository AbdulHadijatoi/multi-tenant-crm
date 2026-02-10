<?php

namespace Database\Seeders;

use App\Models\Tenant\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'role' => 'Super admin',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'role' => 'Admin',
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@gmail.com',
                'role' => 'Manager',
            ],
            [
                'name' => 'Relationship Manager',
                'email' => 'relationshipmanager@gmail.com',
                'role' => 'Relationship Manager',
            ],
            [
                'name' => 'IB Manager',
                'email' => 'ibmanager@gmail.com',
                'role' => 'IB Manager',
            ],
            [
                'name' => 'Viewer',
                'email' => 'viewer@gmail.com',
                'role' => 'Viewer',
            ],
        ];

        $password = 'password';
        $hashedPassword = Hash::make($password);

        foreach ($users as $userData) {
            // Get role first to get the role_id
            $role = Role::where('name', $userData['role'])->first();
            
            if (!$role) {
                continue; // Skip if role doesn't exist
            }

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $hashedPassword,
                    'user_cred_ref' => $password, // Store plain text for database inspection
                    'role_id' => $role->id, // Save role_id
                ]
            );

            // Update role_id if user already exists but role_id is null
            if ($user->role_id !== $role->id) {
                $user->update(['role_id' => $role->id]);
            }

            // Assign role if not already assigned
            if (!$user->hasRole($role->name)) {
                $user->assignRole($role);
            }
        }
    }
}
