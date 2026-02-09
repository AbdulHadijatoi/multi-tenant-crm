<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
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

        // Only allow Super admin role
        if (! $user->hasRole('Super admin')) {
            return $this->error(
                __('auth.unauthorized_role'),
                [],
                403,
                'UNAUTHORIZED_ROLE'
            );
        }

        $token = $user->createToken('superadmin_token')->plainTextToken;

        return $this->success([
            'user' => $user->load('roles'),
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
