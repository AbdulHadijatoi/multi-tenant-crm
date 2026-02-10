<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\Wallet;
use App\Models\Tenant\Transaction;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Get wallet balance and overview.
     */
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet ?? Wallet::create(['user_id' => $user->id]);

        return response()->json([
            'status' => true,
            'data' => [
                'currency' => 'USD',
                'total_balance' => $wallet->balance + $wallet->locked_balance,
                'available_balance' => $wallet->balance,
                'locked_balance' => $wallet->locked_balance,
                'ib_commission' => $wallet->commission_balance,
            ]
        ]);
    }

    /**
     * Get transaction history.
     */
    public function transactions(Request $request)
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'status' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Process a new deposit.
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'method' => 'required|string',
        ]);

        $txn = Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'deposit',
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'pending',
            'description' => 'Deposit via ' . $request->method
        ]);

        // In a real app, integrate Payment Gateway logic here (Stripe/Crypto/etc)

        return response()->json([
            'status' => true,
            'message' => 'Deposit initiated successfully.',
            'data' => $txn
        ]);
    }

    /**
     * Process a withdrawal request.
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'address' => 'required|string',
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;

        if ($wallet->balance < $request->amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient available balance.'
            ], 400);
        }

        DB::transaction(function () use ($wallet, $request, $user) {
            // Lock funds
            $wallet->balance -= $request->amount;
            $wallet->locked_balance += $request->amount;
            $wallet->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $request->amount,
                'status' => 'pending',
                'description' => 'Withdrawal to ' . $request->address
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal request submitted.'
        ]);
    }
}
