@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-20">
    <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-6">
        <div class="shrink-0">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400">Welcome back, {{ Auth::user()->name ?? 'Trader' }}.</p>
        </div>
         <!-- Advertisement Placement 1: Top Leaderboard moved here -->
        @if(isset($ads['top']))
        <div class="w-full flex-1 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800">
            <img src="{{ $ads['top'] }}" alt="Special Offer" class="w-full h-20 object-cover transition-transform hover:scale-[1.01]">
        </div>
        @endif
    </div>

    <!-- Row 1: Financials (Wallet Deposit replacing Investment) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('wallet') }}" class="block">
            <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer h-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Wallet Balance</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['balance'], 2) }}</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1 text-sm">
                     <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
                     <span class="text-green-500 font-medium">+12.5%</span>
                     <span class="text-gray-400 ml-1">vs last month</span>
                </div>
            </div>
        </a>

        <!-- Wallet Deposit -->
        <a href="{{ route('wallet', ['tab' => 'deposit']) }}" class="block">
            <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer h-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Wallet Deposit</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['deposits'], 2) }}</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="arrow-down-left" class="w-5 h-5"></i>
                    </div>
                </div>
                 <div class="mt-4 flex items-center gap-1 text-sm">
                     <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
                     <span class="text-green-500 font-medium">+8.4%</span>
                     <span class="text-gray-400 ml-1">vs last month</span>
                </div>
            </div>
        </a>

        <a href="{{ route('wallet', ['tab' => 'withdraw']) }}" class="block">
            <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer h-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Wallet Withdrawal</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['withdrawals'], 2) }}</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1 text-sm">
                     <i data-lucide="arrow-down-right" class="w-4 h-4 text-red-500"></i>
                     <span class="text-red-500 font-medium">+5.2%</span>
                     <span class="text-gray-400 ml-1">vs last month</span>
                </div>
            </div>
        </a>

        <a href="{{ route('wallet') }}" class="block">
            <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer h-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Commissions</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['commissions'], 2) }}</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="banknote" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1 text-sm">
                     <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
                     <span class="text-green-500 font-medium">+$340.00</span>
                     <span class="text-gray-400 ml-1">vs last month</span>
                </div>
            </div>
        </a>
    </div>

    <!-- Row 2: Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Cash Flow Chart -->
        <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Cash Flow Analytics</h2>
            <div class="h-80 w-full flex items-center justify-center bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                <p class="text-gray-500">Deposit vs Withdrawal vs Commission Chart (JS Required)</p>
            </div>
        </div>

        <!-- Wallet Balance Growth (Renamed) -->
        <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Wallet Balance Growth</h2>
            <div class="h-80 w-full flex items-center justify-center bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                <p class="text-gray-500">Growth Area Chart (JS Required)</p>
            </div>
        </div>
    </div>
    
    <!-- Row 3: Product Counts (Total Investment moved here) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('forex') }}" class="block">
            <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Trading Accounts</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['trading_accounts'] }}</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="monitor" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('prop-firm') }}" class="block">
            <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Prop Accounts</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_prop_accounts'] }} Active</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="trophy" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </a>
        
        <!-- Total Investments (Moved Here) -->
        <a href="{{ route('investments') }}" class="block">
            <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer h-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Investments</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['investments'], 2) }}</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('support') }}" class="block">
            <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Support Tickets</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['support_tickets'] }} Open</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="life-buoy" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Row 4: Earnings & Rewards Breakdown -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('forex') }}" class="block">
             <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer h-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">IB Earnings</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['ib_earnings'], 2) }}</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1 text-sm">
                     <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
                     <span class="text-green-500 font-medium">+15%</span>
                     <span class="text-gray-400 ml-1">vs last month</span>
                </div>
            </div>
        </a>
        
        <a href="{{ route('prop-firm') }}" class="block">
             <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer h-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Affiliate Earnings</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['affiliate_earnings'], 2) }}</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="share-2" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1 text-sm">
                     <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
                     <span class="text-green-500 font-medium">+5%</span>
                     <span class="text-gray-400 ml-1">vs last month</span>
                </div>
            </div>
        </a>

        <a href="{{ route('investments') }}" class="block">
             <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer h-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Inv. Referral Earnings</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['referral_earnings'], 2) }}</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1 text-sm">
                     <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
                     <span class="text-green-500 font-medium">+8%</span>
                     <span class="text-gray-400 ml-1">vs last month</span>
                </div>
            </div>
        </a>

        <a href="{{ route('loyalty') }}" class="block">
            <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all cursor-pointer h-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Loyalty Points</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['loyalty_points'] }} Pts</h3>
                    </div>
                    <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                        <i data-lucide="gift" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1 text-sm">
                     <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
                     <span class="text-green-500 font-medium">+150</span>
                     <span class="text-gray-400 ml-1">vs last month</span>
                </div>
            </div>
        </a>
    </div>
    
     <!-- Row 5: NEW CHARTS - Commission Analytics & Total Investment Growth -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Commission Analytics -->
        <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Commission Analytics</h2>
            <div class="h-80 w-full flex items-center justify-center bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                <p class="text-gray-500">IB vs Affiliate vs Referral Chart (JS Required)</p>
            </div>
        </div>

        <!-- Total Investment Growth -->
        <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Total Investment Growth</h2>
            <div class="h-80 w-full flex items-center justify-center bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                <p class="text-gray-500">Investment Growth Area Chart (JS Required)</p>
            </div>
        </div>
    </div>
    
    <!-- Advertisement 2: Leaderboard Bottom -->
    @if(isset($ads['bottom']))
    <div class="w-full rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800 hidden sm:block">
        <img src="{{ $ads['bottom'] }}" alt="Promotion" class="w-full h-24 md:h-32 object-cover transition-transform hover:scale-[1.01]">
    </div>
    @endif

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-dark-card rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white">Recent Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-green-600 dark:text-green-400 capitalize flex items-center gap-2">
                             <i data-lucide="arrow-down-left" class="w-4 h-4"></i> Deposit
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400">+$5,000.00</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">2023-10-25</td>
                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Completed</span></td>
                        <td class="px-6 py-4 text-sm text-right">
                            <a href="{{ route('wallet') }}" class="inline-flex items-center gap-1 text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 font-medium text-xs">
                                <i data-lucide="eye" class="w-4 h-4"></i> View
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-red-600 dark:text-red-400 capitalize flex items-center gap-2">
                             <i data-lucide="arrow-up-right" class="w-4 h-4"></i> Withdrawal
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-red-600 dark:text-red-400">-$1,000.00</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">2023-10-27</td>
                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded text-xs font-bold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span></td>
                        <td class="px-6 py-4 text-sm text-right">
                             <a href="{{ route('wallet') }}" class="inline-flex items-center gap-1 text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 font-medium text-xs">
                                <i data-lucide="eye" class="w-4 h-4"></i> View
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection