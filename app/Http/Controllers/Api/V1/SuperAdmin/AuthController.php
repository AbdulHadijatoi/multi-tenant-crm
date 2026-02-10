<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Master\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login SuperAdmin user and create token.
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

        // Only allow SuperAdmin role with master guard
        if (! $user->hasRole('SuperAdmin', 'master')) {
            return $this->error(
                __('auth.unauthorized_role'),
                [],
                403,
                'UNAUTHORIZED_ROLE'
            );
        }

        $token = $user->createToken('superadmin_token')->plainTextToken;

        // Get role information from Spatie
        $firstRole = $user->roles->first();
        $roleId = $firstRole ? $firstRole->id : null;
        $roleName = $firstRole ? $firstRole->name : null;

        // Prepare user data without roles object
        $userData = $user->toArray();
        unset($userData['roles']);
        $userData['role_id'] = $roleId;
        $userData['role_name'] = $roleName;

        return $this->success([
            'user' => $userData,
            'token' => $token,
        ], 200, __('auth.login_success'));
    }

    /**
     * Logout SuperAdmin user (revoke token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 200, __('auth.logout_success'));
    }
}
