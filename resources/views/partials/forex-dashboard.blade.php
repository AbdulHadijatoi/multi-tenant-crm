
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
        <p class="text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
    </div>
    @if(!$isAccount)
    <div class="flex gap-2">
        <button @click="activeSection = 'accounts'" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-bold transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> New Account
        </button>
    </div>
    @endif
</div>

<!-- Row 1: Account Health Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Equity</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">$15,350.20</h3>
            </div>
            <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                <i data-lucide="activity" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-sm">
            <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
            <span class="text-green-500 font-medium">+5.2%</span>
            <span class="text-gray-400 ml-1">vs yesterday</span>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Balance</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">$14,800.00</h3>
            </div>
            <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                <i data-lucide="dollar-sign" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-sm">
            <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
            <span class="text-green-500 font-medium">+2.1%</span>
            <span class="text-gray-400 ml-1">vs yesterday</span>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Used Margin</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">$1,240.00</h3>
            </div>
            <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-sm">
            <span class="text-gray-400 ml-1">Level: 1,240%</span>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Profit Today</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">+$245.50</h3>
            </div>
            <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-sm">
            <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
            <span class="text-green-500 font-medium">+12%</span>
            <span class="text-gray-400 ml-1">Daily Goal: $200</span>
        </div>
    </div>
</div>

<!-- Row 2: Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- PnL Growth Chart -->
    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Profit Growth (This Week)</h2>
        <div class="h-80 w-full flex items-center justify-center bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
            <p class="text-gray-500">Area Chart (JS Required)</p>
        </div>
    </div>

    <!-- Trading Volume Chart -->
    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Volume Traded (Lots)</h2>
        <div class="h-80 w-full flex items-center justify-center bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
            <p class="text-gray-500">Bar Chart (JS Required)</p>
        </div>
    </div>
</div>

<!-- Row 3: Trading Performance Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Active Trades</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">5</h3>
            </div>
            <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                <i data-lucide="activity" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-400">3 Buy / 2 Sell</div>
    </div>

    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pending Orders</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">2</h3>
            </div>
            <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-400">Limits & Stops</div>
    </div>

    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Win Rate</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">68%</h3>
            </div>
            <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                <i data-lucide="percent" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-sm">
            <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-500"></i>
            <span class="text-green-500 font-medium">+2%</span>
            <span class="text-gray-400 ml-1">Last 50 trades</span>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-card p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Lots Traded</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">124.5</h3>
            </div>
            <div class="p-3 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-400">This Month</div>
    </div>
</div>

<!-- Row 4: Active Positions Table -->
<div class="bg-white dark:bg-dark-card rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <h3 class="font-bold text-gray-900 dark:text-white">Live Open Positions</h3>
        @if(!$isAccount)
        <button @click="activeSection = 'accounts'" class="text-sm text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1">
            View All Accounts <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Symbol</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Volume</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Open Price</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Current Price</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Profit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach([
                    ['symbol' => 'EURUSD', 'type' => 'Buy', 'vol' => 1.00, 'open' => 1.0820, 'curr' => 1.0845, 'profit' => 250],
                    ['symbol' => 'GBPUSD', 'type' => 'Sell', 'vol' => 0.50, 'open' => 1.2650, 'curr' => 1.2630, 'profit' => 100],
                    ['symbol' => 'XAUUSD', 'type' => 'Buy', 'vol' => 0.10, 'open' => 2020.00, 'curr' => 2015.00, 'profit' => -50],
                    ['symbol' => 'USDJPY', 'type' => 'Buy', 'vol' => 2.00, 'open' => 149.50, 'curr' => 149.80, 'profit' => 400],
                ] as $pos)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $pos['symbol'] }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $pos['type'] === 'Buy' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $pos['type'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ number_format($pos['vol'], 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ number_format($pos['open'], 5) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ number_format($pos['curr'], 5) }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-right {{ $pos['profit'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        {{ $pos['profit'] >= 0 ? '+' : '' }}{{ number_format($pos['profit'], 2) }} USD
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
