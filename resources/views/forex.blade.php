
@extends('layouts.app')

@section('content')
<div class="w-full"
     x-data="{ 
        activeSection: '{{ request()->query('tab', 'dashboard') }}',
        accountType: 'live',
        financeType: 'deposit',
        offerType: 'bonus',
        selectedAccount: null,
        navigateToFinance(type) {
            this.activeSection = 'finance';
            this.financeType = type;
        }
     }">

    <!-- CONTENT AREA - No Sidebar -->
    <div class="animate-fade-in">
        
        <!-- DASHBOARD VIEW (AGGREGATED) -->
        <div x-show="activeSection === 'dashboard'" class="space-y-6 pb-20">
             @include('partials.forex-dashboard', ['title' => 'Forex Dashboard', 'subtitle' => 'Real-time overview of all trading activity.', 'isAccount' => false])
        </div>

        <!-- TRADING ACCOUNTS VIEW -->
        <div x-show="activeSection === 'accounts'" class="space-y-6">
             <!-- LIST VIEW -->
             <div x-show="!selectedAccount">
                 <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Trading Accounts</h2>
                    <div class="flex gap-2">
                        <button @click="accountType = 'live'" :class="accountType === 'live' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700'" class="px-4 py-2 rounded-lg font-medium transition-colors">Live Account</button>
                        <button @click="accountType = 'demo'" :class="accountType === 'demo' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700'" class="px-4 py-2 rounded-lg font-medium transition-colors">Demo Account</button>
                    </div>
                 </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    @foreach($accounts as $acc)
                    <div x-show="accountType === '{{ strtolower($acc->type) }}'" 
                         class="bg-white dark:bg-dark-card rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden group hover:border-brand-500 dark:hover:border-brand-500 transition-all hover:shadow-md">
                        
                        <!-- Card Body (No Click) -->
                        <div class="cursor-default">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20 group-hover:bg-brand-50/50 dark:group-hover:bg-brand-900/10 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-sm {{ $acc->type === 'Live' ? 'bg-green-600' : 'bg-blue-600' }}">
                                        {{ $acc->platform }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white text-lg flex items-center gap-2">
                                            {{ $acc->login }}
                                            <a href="#" target="_blank" class="p-1 bg-brand-100 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 rounded hover:bg-brand-200 dark:hover:bg-brand-900/50 transition-colors" title="Trade Now">
                                                <i data-lucide="candlestick-chart" class="w-3.5 h-3.5 fill-current"></i>
                                            </a>
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $acc->server }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span class="px-2 py-1 rounded text-xs font-bold uppercase {{ $acc->type === 'Live' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $acc->type }}
                                    </span>
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 px-2 py-0.5 rounded">
                                        {{ $acc->acc_type ?? 'Standard' }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 grid grid-cols-2 gap-y-6 gap-x-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Balance</p>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">${{ $acc->balance }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Equity</p>
                                    <p class="text-xl font-bold text-brand-600 dark:text-brand-400">${{ $acc->equity }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Used Margin</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">${{ $acc->margin }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Leverage</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $acc->leverage }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Toolbar (Visuals Only in Blade, Functionality in React/Alpine Modal) -->
                        <div class="grid {{ strtolower($acc->type) === 'demo' ? 'grid-cols-6' : 'grid-cols-7' }} divide-x divide-gray-100 dark:divide-gray-800 border-t border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/30">
                            <button @click="selectedAccount = {{ json_encode($acc) }}" class="flex flex-col items-center justify-center gap-1 py-3 text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-brand-600 dark:hover:text-brand-400 transition-all group/btn" title="View Dashboard">
                                <i data-lucide="eye" class="w-4 h-4 group-hover/btn:text-brand-600"></i>
                                <span class="text-[10px] font-medium group-hover/btn:text-brand-600">View</span>
                            </button>
                            <!-- Password Change Mock -->
                            <button class="flex flex-col items-center justify-center gap-1 py-3 text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition-all group/btn" title="Change Password">
                                <i data-lucide="lock" class="w-4 h-4 group-hover/btn:text-purple-600"></i>
                                <span class="text-[10px] font-medium group-hover/btn:text-purple-600">Password</span>
                            </button>
                            <!-- Leverage Change Mock -->
                            <button class="flex flex-col items-center justify-center gap-1 py-3 text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-orange-600 dark:hover:text-orange-400 transition-all group/btn" title="Change Leverage">
                                <i data-lucide="gauge" class="w-4 h-4 group-hover/btn:text-orange-600"></i>
                                <span class="text-[10px] font-medium group-hover/btn:text-orange-600">Leverage</span>
                            </button>
                            <button @click="navigateToFinance('deposit')" class="flex flex-col items-center justify-center gap-1 py-3 text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-green-600 dark:hover:text-green-400 transition-all group/btn" title="Deposit Funds">
                                <i data-lucide="arrow-down-circle" class="w-4 h-4 group-hover/btn:text-green-600"></i>
                                <span class="text-[10px] font-medium group-hover/btn:text-green-600">Deposit</span>
                            </button>
                            <button @click="navigateToFinance('withdraw')" class="flex flex-col items-center justify-center gap-1 py-3 text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-red-600 dark:hover:text-red-400 transition-all group/btn" title="Withdraw Funds">
                                <i data-lucide="arrow-up-circle" class="w-4 h-4 group-hover/btn:text-red-600"></i>
                                <span class="text-[10px] font-medium group-hover/btn:text-red-600">Withdraw</span>
                            </button>
                            @if(strtolower($acc->type) !== 'demo')
                            <!-- Transfer Mock -->
                            <button class="flex flex-col items-center justify-center gap-1 py-3 text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 transition-all group/btn" title="Internal Transfer">
                                <i data-lucide="arrow-right-left" class="w-4 h-4 group-hover/btn:text-blue-600"></i>
                                <span class="text-[10px] font-medium group-hover/btn:text-blue-600">Transfer</span>
                            </button>
                            <!-- Bonus Mock -->
                            <button class="flex flex-col items-center justify-center gap-1 py-3 text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-yellow-600 dark:hover:text-yellow-400 transition-all group/btn" title="Claim Bonus">
                                <i data-lucide="gift" class="w-4 h-4 group-hover/btn:text-yellow-600"></i>
                                <span class="text-[10px] font-medium group-hover/btn:text-yellow-600">Bonus</span>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    
                    <button class="border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-xl flex flex-col items-center justify-center p-8 text-gray-400 hover:text-brand-600 hover:border-brand-500 hover:bg-brand-50 dark:hover:bg-brand-900/10 transition-all min-h-[200px]">
                        <i data-lucide="plus" class="w-8 h-8 mb-2"></i>
                        <span class="font-bold">Open New Account</span>
                    </button>
                 </div>
             </div>

             <!-- DETAIL DASHBOARD VIEW -->
             <div x-show="selectedAccount">
                 <div class="space-y-6 pb-20">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <button @click="selectedAccount = null" class="mr-2 p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors">
                                    <i data-lucide="arrow-left" class="w-6 h-6 text-gray-500"></i>
                                </button>
                                Account #<span x-text="selectedAccount?.login"></span> Dashboard
                            </h1>
                            <p class="text-gray-500 dark:text-gray-400 ml-1">
                                Platform: <span x-text="selectedAccount?.platform"></span> | Server: <span x-text="selectedAccount?.server"></span>
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-4 py-2 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-bold transition-colors shadow-sm flex items-center gap-2">
                                <i data-lucide="settings" class="w-4 h-4"></i> Settings
                            </button>
                        </div>
                    </div>
                    
                    @include('partials.forex-dashboard', ['title' => '', 'subtitle' => '', 'isAccount' => true])
                 </div>
             </div>
        </div>

        <!-- FINANCE VIEW -->
        <div x-show="activeSection === 'finance'" class="space-y-6">
             <!-- ... (Keep existing finance implementation) ... -->
             <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Finance</h2>
                <div class="flex gap-2">
                    <button @click="financeType = 'deposit'" :class="financeType === 'deposit' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700'" class="px-4 py-2 rounded-lg font-medium transition-colors">Deposit</button>
                    <button @click="financeType = 'withdraw'" :class="financeType === 'withdraw' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700'" class="px-4 py-2 rounded-lg font-medium transition-colors">Withdraw</button>
                    <button @click="financeType = 'transfer'" :class="financeType === 'transfer' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700'" class="px-4 py-2 rounded-lg font-medium transition-colors">Transfer</button>
                    <button @click="financeType = 'history'" :class="financeType === 'history' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700'" class="px-4 py-2 rounded-lg font-medium transition-colors">History</button>
                </div>
            </div>
            
             <div class="bg-white dark:bg-dark-card rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
                 <!-- Deposit Form - RESTRICTED TO WALLET ONLY -->
                 <div x-show="financeType === 'deposit'" class="max-w-xl mx-auto">
                     <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-900 dark:text-white">
                         <i data-lucide="arrow-down-left" class="text-green-500"></i> Deposit to Trading Account
                     </h3>
                     <!-- Mock Form -->
                     <div class="space-y-4">
                         <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 text-sm rounded-lg mb-4 border border-yellow-100 dark:border-yellow-900/50">
                             Funds will be transferred from your Main Wallet to the selected Trading Account.
                         </div>
                         <!-- Inputs -->
                         <div>
                             <label class="block text-sm font-medium mb-1 dark:text-gray-300">Select Account</label>
                             <select class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                 <option>8829102 (Live) - $5,200.00</option>
                             </select>
                         </div>
                         <button class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg mt-4 shadow-lg shadow-brand-500/20">Process Deposit</button>
                     </div>
                 </div>
                 
                 <!-- Withdraw Form - RESTRICTED TO WALLET ONLY -->
                 <div x-show="financeType === 'withdraw'" class="max-w-xl mx-auto">
                      <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-900 dark:text-white">
                         <i data-lucide="arrow-up-right" class="text-red-500"></i> Withdraw from Trading Account
                     </h3>
                      <div class="space-y-4">
                         <div>
                             <label class="block text-sm font-medium mb-1 dark:text-gray-300">From Account</label>
                             <select class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                 <option>8829102 (Live) - $5,200.00</option>
                             </select>
                         </div>
                         <button class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg mt-4 shadow-lg shadow-brand-500/20">Process Withdrawal</button>
                     </div>
                 </div>

                 <!-- Transactions History Place holder -->
                 <div x-show="financeType === 'history'" class="text-center py-8 text-gray-500">
                     <i data-lucide="history" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                     <p>No transactions found for trading accounts.</p>
                 </div>
                 
                 <!-- Transfer Form -->
                 <div x-show="financeType === 'transfer'" class="max-w-xl mx-auto">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-900 dark:text-white">
                        <i data-lucide="arrow-right-left" class="text-blue-500"></i> Internal Transfer
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-300">From</label>
                                <select class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                    <option>Wallet (USD)</option>
                                    <option>8829102 (Live)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-300">To</label>
                                <select class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                    <option>8829102 (Live)</option>
                                    <option>Wallet (USD)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">Amount</label>
                            <input type="number" class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white" placeholder="0.00">
                        </div>
                        <button class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg mt-4 shadow-lg shadow-brand-500/20">Execute Transfer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- OFFERS, IB, PAMM, COPY (Remaining sections maintained) -->
        <div x-show="activeSection === 'offers'" class="space-y-6">
             <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Offers & Promotions</h2>
                <div class="flex gap-2">
                    <button @click="offerType = 'bonus'" :class="offerType === 'bonus' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700'" class="px-4 py-2 rounded-lg font-medium transition-colors">Bonus</button>
                    <button @click="offerType = 'promotions'" :class="offerType === 'promotions' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700'" class="px-4 py-2 rounded-lg font-medium transition-colors">Promotions</button>
                </div>
            </div>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="offerType === 'bonus'">
                 <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">100% Deposit Bonus</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Double your trading power.</p>
                        </div>
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 text-purple-600 rounded-lg"><i data-lucide="gift" class="w-6 h-6"></i></div>
                    </div>
                    <button class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg transition-colors">Claim Bonus</button>
                </div>
             </div>
        </div>

        <div x-show="activeSection === 'ib'" class="space-y-6">
             <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                 <h2 class="text-xl font-bold text-gray-900 dark:text-white">Introducing Broker (IB) Dashboard</h2>
                 <button class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg font-medium transition-colors">Withdraw Commissions</button>
             </div>
             
             <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                 <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all">
                     <div class="flex justify-between items-start">
                         <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Clients</p><h3 class="text-2xl font-bold text-gray-900 dark:text-white">24</h3></div>
                         <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg"><i data-lucide="users" class="w-6 h-6"></i></div>
                     </div>
                 </div>
                 <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all">
                     <div class="flex justify-between items-start">
                         <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Commission Earned</p><h3 class="text-2xl font-bold text-gray-900 dark:text-white">$1,240.50</h3></div>
                         <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg"><i data-lucide="dollar-sign" class="w-6 h-6"></i></div>
                     </div>
                 </div>
             </div>
        </div>
        
        <div x-show="activeSection === 'pamm'" class="space-y-6">
             <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                 <h2 class="text-xl font-bold text-gray-900 dark:text-white">PAMM / MAM Accounts</h2>
                 <button class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg font-medium transition-colors">Become a Manager</button>
             </div>
             
             <div class="bg-white dark:bg-dark-card rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 dark:text-white">Top Fund Managers</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Manager</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Return</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">Alpha Capital</td>
                                <td class="px-6 py-4 text-sm font-bold text-green-600">+45.2%</td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <button class="px-4 py-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-lg transition-colors">Invest</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
             </div>
        </div>

         <div x-show="activeSection === 'copy'" class="space-y-6">
             <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                 <h2 class="text-xl font-bold text-gray-900 dark:text-white">Social Copy Trading</h2>
                 <button class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg font-medium transition-colors">My Portfolio</button>
             </div>
             
             <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                 <div class="bg-white dark:bg-dark-card rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 flex flex-col hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white font-bold text-lg">A</div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">AlexFxPro</h3>
                                <div class="flex items-center gap-1 text-xs text-gray-500">1240 copiers</div>
                            </div>
                        </div>
                        <div class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-700">Risk: 3/5</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg text-center">
                            <p class="text-xs text-gray-500 mb-1">Total Return</p>
                            <p class="text-xl font-bold text-green-500">+345%</p>
                        </div>
                        <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg text-center">
                            <p class="text-xs text-gray-500 mb-1">This Month</p>
                            <p class="text-xl font-bold text-brand-500">+12.5%</p>
                        </div>
                    </div>
                    <div class="mt-auto flex gap-3">
                        <button class="flex-1 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Stats</button>
                        <button class="flex-1 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-bold transition-colors shadow-lg shadow-brand-500/20">Copy</button>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection
