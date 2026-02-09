
@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ 
    depositMethod: '', 
    depositAmount: '',
    withdrawMethod: '',
    withdrawAmount: '',
    withdrawDetails: ''
}">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Wallet</h1>
        <div class="flex gap-2">
            <a href="{{ route('wallet', ['tab' => 'deposit']) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ $activeTab === 'deposit' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card border border-gray-200 dark:border-gray-700 dark:text-white' }}">Deposit</a>
            <a href="{{ route('wallet', ['tab' => 'withdraw']) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ $activeTab === 'withdraw' ? 'bg-brand-600 text-white' : 'bg-white dark:bg-dark-card border border-gray-200 dark:border-gray-700 dark:text-white' }}">Withdraw</a>
        </div>
    </div>

    @if($activeTab === 'overview')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
        <div class="bg-gradient-to-br from-brand-600 to-brand-800 p-6 rounded-2xl text-white shadow-lg">
            <p class="text-brand-100 text-sm font-medium mb-1">Total Balance</p>
            <h2 class="text-3xl font-bold mb-4">$24,592.50</h2>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-brand-500/50">
                <div><p class="text-xs text-brand-200 uppercase">Available</p><p class="font-semibold text-lg">$18,200.00</p></div>
                <div><p class="text-xs text-brand-200 uppercase">Locked</p><p class="font-semibold text-lg">$6,392.50</p></div>
            </div>
        </div>
    </div>
    @endif

    @if($activeTab === 'deposit')
    <div class="bg-white dark:bg-dark-card rounded-xl p-6 border border-gray-100 dark:border-gray-800 animate-fade-in space-y-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
             <i data-lucide="arrow-down-left" class="text-green-500"></i> Add Funds to Wallet
          </h3>
          
          <div>
             <h4 class="text-sm font-medium text-gray-500 uppercase mb-3">Select Payment Method</h4>
             <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <!-- Bank -->
                  <div @click="depositMethod = 'bank'" class="p-4 border rounded-xl cursor-pointer flex flex-col items-center text-center gap-3 transition-all" :class="depositMethod === 'bank' ? 'border-brand-500 bg-brand-50 dark:bg-brand-900/20 ring-1 ring-brand-500 text-brand-700 dark:text-brand-300' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                      <div class="text-gray-500" :class="{'text-brand-600': depositMethod === 'bank'}"><i data-lucide="landmark" class="w-6 h-6"></i></div>
                      <div>
                          <span class="block font-bold text-sm">Bank Transfer</span>
                          <span class="text-xs text-gray-500 mt-1">1-3 Business Days</span>
                      </div>
                  </div>
                  <!-- Card -->
                  <div @click="depositMethod = 'card'" class="p-4 border rounded-xl cursor-pointer flex flex-col items-center text-center gap-3 transition-all" :class="depositMethod === 'card' ? 'border-brand-500 bg-brand-50 dark:bg-brand-900/20 ring-1 ring-brand-500 text-brand-700 dark:text-brand-300' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                      <div class="text-gray-500" :class="{'text-brand-600': depositMethod === 'card'}"><i data-lucide="credit-card" class="w-6 h-6"></i></div>
                      <div>
                          <span class="block font-bold text-sm">Credit Card</span>
                          <span class="text-xs text-gray-500 mt-1">Instant</span>
                      </div>
                  </div>
                  <!-- Crypto -->
                  <div @click="depositMethod = 'crypto'" class="p-4 border rounded-xl cursor-pointer flex flex-col items-center text-center gap-3 transition-all" :class="depositMethod === 'crypto' ? 'border-brand-500 bg-brand-50 dark:bg-brand-900/20 ring-1 ring-brand-500 text-brand-700 dark:text-brand-300' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                      <div class="text-gray-500" :class="{'text-brand-600': depositMethod === 'crypto'}"><i data-lucide="coins" class="w-6 h-6"></i></div>
                      <div>
                          <span class="block font-bold text-sm">Crypto (USDT)</span>
                          <span class="text-xs text-gray-500 mt-1">Instant • Zero Fees</span>
                      </div>
                  </div>
             </div>
          </div>

          <div x-show="depositMethod" class="animate-fade-in max-w-md">
             <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount (USD)</label>
             <div class="relative">
                 <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">$</span>
                 <input type="number" x-model="depositAmount" placeholder="0.00" class="w-full pl-8 p-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none text-lg font-bold">
             </div>
          </div>

          <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-800">
             <button :disabled="!depositMethod || !depositAmount" class="px-8 py-3 bg-brand-600 hover:bg-brand-700 disabled:bg-gray-300 dark:disabled:bg-gray-800 disabled:text-gray-500 text-white rounded-lg font-bold shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2">
                 <i data-lucide="plus" class="w-4 h-4"></i> Process Deposit
             </button>
          </div>
    </div>
    @endif

    @if($activeTab === 'withdraw')
    <div class="bg-white dark:bg-dark-card rounded-xl p-6 border border-gray-100 dark:border-gray-800 animate-fade-in space-y-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
             <i data-lucide="arrow-up-right" class="text-red-500"></i> Withdraw Funds
          </h3>
          
          <div>
             <h4 class="text-sm font-medium text-gray-500 uppercase mb-3">Select Withdrawal Method</h4>
             <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <!-- Bank -->
                  <div @click="withdrawMethod = 'bank'" class="p-4 border rounded-xl cursor-pointer flex flex-col items-center text-center gap-3 transition-all" :class="withdrawMethod === 'bank' ? 'border-brand-500 bg-brand-50 dark:bg-brand-900/20 ring-1 ring-brand-500 text-brand-700 dark:text-brand-300' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                      <div class="text-gray-500" :class="{'text-brand-600': withdrawMethod === 'bank'}"><i data-lucide="landmark" class="w-6 h-6"></i></div>
                      <div>
                          <span class="block font-bold text-sm">Bank Transfer</span>
                          <span class="text-xs text-gray-500 mt-1">To local bank</span>
                      </div>
                  </div>
                  <!-- Crypto -->
                  <div @click="withdrawMethod = 'crypto'" class="p-4 border rounded-xl cursor-pointer flex flex-col items-center text-center gap-3 transition-all" :class="withdrawMethod === 'crypto' ? 'border-brand-500 bg-brand-50 dark:bg-brand-900/20 ring-1 ring-brand-500 text-brand-700 dark:text-brand-300' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'">
                      <div class="text-gray-500" :class="{'text-brand-600': withdrawMethod === 'crypto'}"><i data-lucide="coins" class="w-6 h-6"></i></div>
                      <div>
                          <span class="block font-bold text-sm">Crypto Wallet</span>
                          <span class="text-xs text-gray-500 mt-1">USDT/BTC</span>
                      </div>
                  </div>
             </div>
          </div>

          <div x-show="withdrawMethod" class="animate-fade-in max-w-md space-y-4">
             <div>
                 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount to Withdraw (USD)</label>
                 <div class="relative">
                     <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">$</span>
                     <input type="number" x-model="withdrawAmount" placeholder="0.00" class="w-full pl-8 p-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none text-lg font-bold">
                 </div>
             </div>
             <div>
                 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" x-text="withdrawMethod === 'crypto' ? 'Wallet Address (TRC20)' : 'Account Number / IBAN'"></label>
                 <input type="text" x-model="withdrawDetails" :placeholder="withdrawMethod === 'crypto' ? 'Enter wallet address' : 'Enter account details'" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none">
             </div>
          </div>

          <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-800">
             <button :disabled="!withdrawMethod || !withdrawAmount || !withdrawDetails" class="px-8 py-3 bg-brand-600 hover:bg-brand-700 disabled:bg-gray-300 dark:disabled:bg-gray-800 disabled:text-gray-500 text-white rounded-lg font-bold shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2">
                 <i data-lucide="arrow-down-right" class="w-4 h-4"></i> Submit Request
             </button>
          </div>
    </div>
    @endif

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-dark-card rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white">Transaction History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Description</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($transactions as $txn)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-mono text-gray-500 dark:text-gray-400">{{ $txn->id }}</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">{{ $txn->type }}</span></td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $txn->desc }}</td>
                        <td class="px-6 py-4 text-sm font-bold {{ $txn->type == 'withdrawal' ? 'text-red-500' : 'text-green-500' }}">
                            {{ $txn->type == 'withdrawal' ? '-' : '+' }}${{ number_format($txn->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm {{ $txn->status == 'completed' ? 'text-green-500' : 'text-yellow-500' }}">{{ ucfirst($txn->status) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
