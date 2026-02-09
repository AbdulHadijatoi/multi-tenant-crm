<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BridgeXController extends Controller
{
    /**
     * Show the main dashboard.
     */
    public function dashboard()
    {
        // Mock data for the view
        $stats = [
            'balance' => 24592.50,
            'deposits' => 12500.00,
            'withdrawals' => 8450.00,
            'active_prop_accounts' => 2,
            'trading_accounts' => 4,
            'investments' => 5200.00,
            'commissions' => 2121.25, // Total commissions
            'ib_earnings' => 1250.50,
            'affiliate_earnings' => 550.00,
            'referral_earnings' => 320.75,
            'support_tickets' => 1,
            'loyalty_points' => 1250,
        ];
        
        // Admin Configurable Ads
        $ads = [
            'top' => 'https://placehold.co/1200x120/2563eb/ffffff?text=Special+Offer:+Get+100%25+Deposit+Bonus+Now!',
            'bottom' => 'https://placehold.co/1200x120/16a34a/ffffff?text=New+Feature:+Zero+Commission+Crypto+Trading'
        ];
        
        return view('dashboard', compact('stats', 'ads'));
    }

    public function wallet(Request $request)
    {
        // Handle tab selection via query parameter
        $activeTab = $request->query('tab', 'overview');

        $transactions = [
            (object)['id' => 'TXN-1001', 'type' => 'deposit', 'amount' => 5000, 'status' => 'completed', 'date' => '2023-10-25', 'desc' => 'Bank Transfer'],
            (object)['id' => 'TXN-1002', 'type' => 'profit', 'amount' => 125.50, 'status' => 'completed', 'date' => '2023-10-26', 'desc' => 'Prop Payout'],
            (object)['id' => 'TXN-1003', 'type' => 'withdrawal', 'amount' => 1000, 'status' => 'pending', 'date' => '2023-10-27', 'desc' => 'USDT Withdrawal'],
        ];

        return view('wallet', compact('transactions', 'activeTab'));
    }

    public function kyc()
    {
        return view('kyc');
    }

    public function forex()
    {
        $accounts = [
            (object)['login' => 8829102, 'server' => 'BridgeX-Live', 'leverage' => '1:500', 'balance' => 5200.00, 'equity' => 5350.20, 'margin' => 1240.00, 'type' => 'Live', 'acc_type' => 'ECN', 'platform' => 'MT5'],
            (object)['login' => 1002931, 'server' => 'BridgeX-Demo', 'leverage' => '1:100', 'balance' => 10000.00, 'equity' => 9800.00, 'margin' => 550.00, 'type' => 'Demo', 'acc_type' => 'Standard', 'platform' => 'MT4'],
            (object)['login' => 8829105, 'server' => 'BridgeX-Live', 'leverage' => '1:200', 'balance' => 0.00, 'equity' => 0.00, 'margin' => 0.00, 'type' => 'Live', 'acc_type' => 'Pro', 'platform' => 'MT5'],
        ];

        return view('forex', compact('accounts'));
    }

    public function propFirm()
    {
        $challenges = [
            (object)['size' => '10K', 'price' => '$99', 'active' => false],
            (object)['size' => '50K', 'price' => '$299', 'active' => true],
            (object)['size' => '100K', 'price' => '$499', 'active' => false],
        ];

        return view('prop-firm', compact('challenges'));
    }

    public function investments()
    {
        return view('investments');
    }
    
    public function loyalty()
    {
        return view('loyalty');
    }

    public function p2p()
    {
        return view('p2p');
    }

    public function copyTrading()
    {
        $traders = [
            (object)['name' => 'AlexFxPro', 'return' => '+345%', 'copiers' => 1240, 'risk' => 3],
            (object)['name' => 'SafeHaven', 'return' => '+89%', 'copiers' => 5400, 'risk' => 1],
            (object)['name' => 'CryptoWhale', 'return' => '+820%', 'copiers' => 890, 'risk' => 5],
        ];
        return view('copy-trading', compact('traders'));
    }

    public function aiCenter()
    {
        return view('ai-center');
    }
    
    public function widgets()
    {
        return view('widgets');
    }
    
    public function download()
    {
        return view('download');
    }

    public function support()
    {
        return view('support');
    }

    public function profile()
    {
        return view('profile');
    }
    
    public function settings()
    {
        return view('settings');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}