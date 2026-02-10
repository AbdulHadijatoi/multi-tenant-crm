<?php

namespace Database\Seeders\Master;

use App\Models\Master\Role;
use App\Models\Master\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create SuperAdmin role with 'master' guard
        $role = Role::firstOrCreate(
            [
                'name' => 'SuperAdmin',
                'guard_name' => 'master',
            ]
        );

        // Get SuperAdmin user credentials from environment or use defaults
        $superAdminEmail = env('MASTER_SUPERADMIN_EMAIL', 'superadmin@gmail.com');
        $superAdminPassword = env('MASTER_SUPERADMIN_PASSWORD', 'password');
        $superAdminName = env('MASTER_SUPERADMIN_NAME', 'Super Admin');

        // Create or update SuperAdmin user
        $user = User::firstOrCreate(
            ['email' => $superAdminEmail],
            [
                'name' => $superAdminName,
                'password' => Hash::make($superAdminPassword),
                'user_cred_ref' => $superAdminPassword,
                'role_id' => $role->id,
            ]
        );

        // Update password if it was changed in env
        if (!Hash::check($superAdminPassword, $user->password)) {
            $user->update(['password' => Hash::make($superAdminPassword)]);
        }

        // Update role_id if user already exists but role_id is null or different
        if ($user->role_id !== $role->id) {
            $user->update(['role_id' => $role->id]);
        }

        // Assign SuperAdmin role if not already assigned
        if (!$user->hasRole($role, 'master')) {
            $user->assignRole($role);
        }

        $this->command->info('SuperAdmin user created successfully!');
        $this->command->info('Email: ' . $superAdminEmail);
        $this->command->info('Password: ' . $superAdminPassword);
    }
}
