<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\PropChallenge;

class PropFirmController extends Controller
{
    public function plans()
    {
        $plans = [
            ['id' => 1, 'size' => '10K', 'price' => 99, 'currency' => 'USD'],
            ['id' => 2, 'size' => '25K', 'price' => 199, 'currency' => 'USD'],
            ['id' => 3, 'size' => '50K', 'price' => 299, 'currency' => 'USD'],
            ['id' => 4, 'size' => '100K', 'price' => 499, 'currency' => 'USD'],
            ['id' => 5, 'size' => '200K', 'price' => 999, 'currency' => 'USD'],
        ];

        return response()->json([
            'status' => true,
            'data' => $plans
        ]);
    }

    public function myChallenges()
    {
        $challenges = PropChallenge::where('user_id', Auth::id())->get();
        return response()->json([
            'status' => true,
            'data' => $challenges
        ]);
    }

    public function purchase(Request $request)
    {
        $request->validate(['plan_id' => 'required|integer']);

        // Logic to charge wallet and provision challenge account
        $challenge = PropChallenge::create([
            'user_id' => Auth::id(),
            'plan_size' => '50K', // Dynamic based on plan_id
            'status' => 'active',
            'phase' => 1,
            'start_date' => now(),
            'account_login' => rand(80000, 89999),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Challenge purchased successfully. Credentials sent to email.',
            'data' => $challenge
        ]);
    }
}
