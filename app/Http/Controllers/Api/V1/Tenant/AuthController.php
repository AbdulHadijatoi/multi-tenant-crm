<?php

namespace App\Http\Controllers\Api\V1\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Login user and create token.
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error(
                __('auth.failed'),
                [],
                401,
                'INVALID_CREDENTIALS'
            );
        }

        // Check if user has an allowed role for tenant login
        $allowedRoles = ['Admin', 'Manager', 'Relationship Manager', 'IB Manager', 'Viewer'];
        $userRoles = $user->getRoleNames()->toArray();
        
        $hasAllowedRole = !empty(array_intersect($allowedRoles, $userRoles));
        
        // Block Super admin from logging in via tenant endpoint
        if ($user->hasRole('Super admin')) {
            return $this->error(
                __('auth.unauthorized_role'),
                [],
                403,
                'UNAUTHORIZED_ROLE'
            );
        }
        
        if (!$hasAllowedRole) {
            return $this->error(
                __('auth.unauthorized_role'),
                [],
                403,
                'UNAUTHORIZED_ROLE'
            );
        }

        $tenant = $request->attributes->get('tenant') ?? (object) ['id' => 1];
        $token = $user->createToken('tenant_'.$tenant->id)->plainTextToken;

        // Get role_id and role_name instead of roles object
        $roleId = $user->role_id;
        $roleName = null;
        if ($roleId) {
            $user->load('roleModel');
            if ($user->roleModel) {
                $roleName = $user->roleModel->name;
            }
        }
        
        // Fallback to first role if role_id is not set
        if (!$roleName) {
            $firstRole = $user->roles->first();
            if ($firstRole) {
                $roleId = $firstRole->id;
                $roleName = $firstRole->name;
            }
        }

        // Prepare user data without roles and role_model objects
        $userData = $user->toArray();
        unset($userData['roles'], $userData['role_model']);
        $userData['role_id'] = $roleId;
        $userData['role_name'] = $roleName;

        return $this->success([
            'user' => $userData,
            'token' => $token,
        ], 200, __('auth.login_success'));
    }

    /**
     * Register new user.
     */
    public function register(RegisterRequest $request)
    {
        $tenant = $request->attributes->get('tenant') ?? (object) ['id' => 1];

        // Determine role - use provided role or default to Viewer
        $roleName = $request->role ?? 'Viewer';
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return $this->error(
                __('auth.invalid_role'),
                [],
                400,
                'INVALID_ROLE'
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_cred_ref' => $request->password, // Store plain text for database inspection
            'role_id' => $role->id, // Save role_id
        ]);

        // Assign role using Spatie
        $user->assignRole($role);

        $token = $user->createToken('tenant_'.$tenant->id)->plainTextToken;

        // Get role_id and role_name instead of roles object
        $roleId = $user->role_id;
        $roleName = $role->name; // We already have the role object

        // Prepare user data without roles and role_model objects
        $userData = $user->toArray();
        unset($userData['roles'], $userData['role_model']);
        $userData['role_id'] = $roleId;
        $userData['role_name'] = $roleName;

        return $this->created([
            'user' => $userData,
            'token' => $token,
        ], __('auth.register_success'));
    }

    /**
     * Logout user (revoke token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 200, __('auth.logout_success'));
    }

    /**
     * Send password reset email.
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $this->success(
                ['message' => __('passwords.sent')],
                200,
                __('passwords.sent')
            );
        }

        return $this->error(
            __('passwords.user'),
            [],
            400,
            'PASSWORD_RESET_FAILED'
        );
    }

    /**
     * Reset password using token.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->user_cred_ref = $password; // Store plain text for database inspection
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->success(
                ['message' => __('passwords.reset')],
                200,
                __('passwords.reset')
            );
        }

        return $this->error(
            __('passwords.token'),
            [],
            400,
            'PASSWORD_RESET_FAILED'
        );
    }

    /**
     * Change password for authenticated user.
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return $this->error(
                __('passwords.current_password_incorrect'),
                [],
                400,
                'INVALID_CURRENT_PASSWORD'
            );
        }

        $user->password = Hash::make($request->password);
        $user->user_cred_ref = $request->password; // Store plain text for database inspection
        $user->save();

        return $this->success(
            ['message' => __('passwords.password_changed')],
            200,
            __('passwords.password_changed')
        );
    }

    /**
     * Get authenticated user profile.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        // Get role_id and role_name instead of roles object
        $roleId = $user->role_id;
        $roleName = null;
        if ($roleId) {
            $user->load('roleModel');
            if ($user->roleModel) {
                $roleName = $user->roleModel->name;
            }
        }
        
        // Fallback to first role if role_id is not set
        if (!$roleName) {
            $firstRole = $user->roles->first();
            if ($firstRole) {
                $roleId = $firstRole->id;
                $roleName = $firstRole->name;
            }
        }

        // Prepare user data without roles and role_model objects
        $userData = $user->toArray();
        unset($userData['roles'], $userData['role_model']);
        $userData['role_id'] = $roleId;
        $userData['role_name'] = $roleName;

        return $this->success([
            'user' => $userData,
        ], 200, __('auth.profile_retrieved'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Get role_id and role_name instead of roles object
        $roleId = $user->role_id;
        $roleName = null;
        if ($roleId) {
            $user->load('roleModel');
            if ($user->roleModel) {
                $roleName = $user->roleModel->name;
            }
        }
        
        // Fallback to first role if role_id is not set
        if (!$roleName) {
            $firstRole = $user->roles->first();
            if ($firstRole) {
                $roleId = $firstRole->id;
                $roleName = $firstRole->name;
            }
        }

        // Prepare user data without roles and role_model objects
        $userData = $user->toArray();
        unset($userData['roles'], $userData['role_model']);
        $userData['role_id'] = $roleId;
        $userData['role_name'] = $roleName;

        return $this->success([
            'user' => $userData,
        ], 200, __('auth.profile_updated'));
    }
}
