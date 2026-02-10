<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\TradingAccount;

class ForexController extends Controller
{
    public function index()
    {
        $accounts = TradingAccount::where('user_id', Auth::id())->get();

        return response()->json([
            'status' => true,
            'data' => $accounts
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required|in:MT4,MT5',
            'type' => 'required|in:Live,Demo',
            'leverage' => 'required|string',
        ]);

        // Simulate creating account on MT4/MT5 Server API
        $loginId = rand(100000, 999999); 

        $account = TradingAccount::create([
            'user_id' => Auth::id(),
            'platform' => $request->platform,
            'login_id' => $loginId,
            'server_name' => 'BridgeX-' . $request->type,
            'type' => $request->type,
            'leverage' => $request->leverage,
            'balance' => $request->type === 'Demo' ? 10000 : 0,
            'equity' => $request->type === 'Demo' ? 10000 : 0,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Trading account created successfully.',
            'data' => $account
        ]);
    }
}
