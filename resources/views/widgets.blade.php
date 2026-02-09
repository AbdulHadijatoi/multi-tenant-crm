@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i data-lucide="layout-grid" class="text-brand-600 dark:text-brand-400"></i> Dashboard Widgets
          </h1>
          <p class="text-gray-500 dark:text-gray-400">Customize your dashboard by adding or removing widgets.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Static examples representing state from backend -->
        @php
            $widgets = [
                ['id' => '1', 'name' => 'Wallet Summary', 'desc' => 'Shows total balance and breakdown by currency.', 'icon' => 'wallet', 'color' => 'text-blue-500', 'size' => 'Medium', 'active' => true],
                ['id' => '2', 'name' => 'Market Watch', 'desc' => 'Live forex and crypto rates ticker.', 'icon' => 'activity', 'color' => 'text-green-500', 'size' => 'Large', 'active' => true],
                ['id' => '3', 'name' => 'Prop Challenge Status', 'desc' => 'Progress bar for current trading challenge.', 'icon' => 'trophy', 'color' => 'text-yellow-500', 'size' => 'Medium', 'active' => true],
                ['id' => '4', 'name' => 'Economic Calendar', 'desc' => 'Upcoming high-impact financial events.', 'icon' => 'calendar', 'color' => 'text-red-500', 'size' => 'Medium', 'active' => false],
                ['id' => '5', 'name' => 'Investment ROI', 'desc' => 'Chart showing monthly investment returns.', 'icon' => 'pie-chart', 'color' => 'text-purple-500', 'size' => 'Medium', 'active' => false],
                ['id' => '6', 'name' => 'News Feed', 'desc' => 'Latest financial news headlines.', 'icon' => 'newspaper', 'color' => 'text-gray-500', 'size' => 'Large', 'active' => false],
                ['id' => '7', 'name' => 'Performance Analytics', 'desc' => 'Track Today P&L, Week/Month performance, and Best/Worst Instruments. Includes Forex/Prop/Copy toggles.', 'icon' => 'bar-chart-2', 'color' => 'text-indigo-500', 'size' => 'Large', 'active' => false],
            ];
        @endphp

        @foreach($widgets as $widget)
        <div class="group relative bg-white dark:bg-dark-card rounded-xl border transition-all duration-200 {{ $widget['active'] ? 'border-brand-500 ring-1 ring-brand-500/20' : 'border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700' }}">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <i data-lucide="{{ $widget['icon'] }}" class="w-6 h-6 {{ $widget['color'] }}"></i>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded">
                        {{ $widget['size'] }}
                    </span>
                </div>
              
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2">{{ $widget['name'] }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 min-h-[40px]">{{ $widget['desc'] }}</p>
              
                <button class="w-full py-2.5 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors {{ $widget['active'] ? 'bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/30' : 'bg-brand-600 text-white hover:bg-brand-700' }}">
                    @if($widget['active'])
                        <i data-lucide="check" class="w-4 h-4"></i> Added to Dashboard
                    @else
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Widget
                    @endif
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection