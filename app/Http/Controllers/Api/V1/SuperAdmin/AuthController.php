<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Auth\SuperAdminLoginRequest;
use App\Models\Master\User;
use App\Models\Master\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login SuperAdmin user and create token.
     */
    public function login(SuperAdminLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error(
                null,
                [],
                401,
                'INVALID_CREDENTIALS'
            );
        }

        // Only allow SuperAdmin role with master guard
        if (! $user->hasRole('SuperAdmin', 'master')) {
            return $this->error(
                null,
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
        $user = $request->user();
        
        if ($user) {
            // Get the bearer token from the request
            $bearerToken = $request->bearerToken();
            
            if ($bearerToken) {
                // Extract the token part (after |)
                $tokenParts = explode('|', $bearerToken, 2);
                $actualToken = count($tokenParts) === 2 ? $tokenParts[1] : $bearerToken;
                
                // Hash the token to find it in the database
                $tokenHash = hash('sha256', $actualToken);
                
                // Delete the token directly using PersonalAccessToken model (uses master connection)
                PersonalAccessToken::where('token', $tokenHash)
                    ->where('tokenable_id', $user->id)
                    ->where('tokenable_type', get_class($user))
                    ->delete();
            }
        }

        return $this->success(null, 200, __('auth.logout_success'));
    }

    /**
     * Get authenticated superadmin user profile.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

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
        ], 200, __('auth.profile_retrieved'));
    }
}
