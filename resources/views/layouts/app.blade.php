
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BridgeX Suite v1.0</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              brand: {
                50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 900: '#0c4a6e',
              },
              dark: {
                bg: '#0f172a', card: '#1e293b', text: '#f8fafc', muted: '#94a3b8'
              }
            },
            animation: {
              ticker: 'ticker 45s linear infinite',
              marquee: 'marquee 35s linear infinite',
            },
            keyframes: {
              ticker: {
                '0%': { transform: 'translateX(0)' },
                '100%': { transform: 'translateX(-50%)' },
              },
              marquee: {
                '0%': { transform: 'translateX(0)' },
                '100%': { transform: 'translateX(-50%)' },
              }
            }
          }
        }
      }
    </script>
    <style>
      .custom-scrollbar::-webkit-scrollbar { width: 6px; }
      .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
      .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
      .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-dark-bg dark:text-dark-text transition-colors duration-200 font-sans antialiased">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-dark-card border-r border-gray-200 dark:border-gray-800 flex flex-col shadow-xl lg:shadow-none fixed inset-y-0 left-0 z-30 transform transition-transform duration-300 lg:static lg:translate-x-0"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-800 shrink-0 justify-between lg:justify-start">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-brand-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md bg-gradient-to-br from-brand-500 to-brand-700">B</div>
                    <div class="flex flex-col">
                        <span class="text-lg font-bold tracking-tight leading-none text-gray-900 dark:text-white">BridgeX Suite</span>
                        <span class="text-[10px] font-semibold text-brand-600 dark:text-brand-400 uppercase tracking-widest leading-none mt-1">Version 1.0</span>
                    </div>
                </div>
                <!-- Mobile Close Button -->
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto custom-scrollbar flex flex-col">
                <!-- User Profile Section -->
                <div class="flex flex-col items-center pt-8 pb-8 px-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-black/20">
                    <a href="{{ route('profile') }}" class="flex flex-col items-center group cursor-pointer">
                        <div class="w-16 h-16 rounded-full p-[2px] bg-gradient-to-tr from-brand-500 to-purple-500 mb-3 shadow-md group-hover:scale-105 transition-transform">
                             <img src="https://picsum.photos/200" alt="Profile" class="w-full h-full rounded-full border-2 border-white dark:border-dark-card object-cover" />
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">{{ Auth::user()->name ?? 'John Trader' }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? 'john@bridgex.com' }}</p>
                    </a>
                </div>

                <div class="p-4 space-y-1 flex-1">
                    <x-nav-link href="{{ route('dashboard') }}" icon="layout-dashboard" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link href="{{ route('wallet') }}" icon="wallet" :active="request()->routeIs('wallet')">Wallet</x-nav-link>
                    <x-nav-link href="{{ route('kyc') }}" icon="shield-check" :active="request()->routeIs('kyc')">KYC Verification</x-nav-link>
                    
                    <!-- Expandable Forex Menu -->
                    <div x-data="{ expanded: {{ request()->routeIs('forex') ? 'true' : 'false' }} }">
                        <button @click="expanded = !expanded" 
                                class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('forex') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="candlestick-chart" class="w-5 h-5"></i>
                                <span>Forex Trading</span>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 transition-transform duration-200" :class="{'rotate-90': expanded}"></i>
                        </button>
                        
                        <div x-show="expanded" x-collapse class="pl-4 space-y-1 mt-1">
                            @php
                                $forexTabs = [
                                    'dashboard' => ['label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                                    'accounts' => ['label' => 'Trading Accounts', 'icon' => 'briefcase'],
                                    'finance' => ['label' => 'Finance', 'icon' => 'wallet'],
                                    'offers' => ['label' => 'Offers', 'icon' => 'gift'],
                                    'ib' => ['label' => 'IB Dashboard', 'icon' => 'users'],
                                    'pamm' => ['label' => 'PAMM', 'icon' => 'pie-chart'],
                                    'copy' => ['label' => 'Copy Trading', 'icon' => 'copy']
                                ];
                                $currentTab = request()->query('tab', 'dashboard');
                            @endphp
                            
                            @foreach($forexTabs as $key => $tab)
                                <a href="{{ route('forex', ['tab' => $key]) }}" 
                                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 border-l-2 {{ request()->routeIs('forex') && $currentTab == $key ? 'border-brand-500 text-brand-600 dark:text-brand-400 bg-brand-50/50 dark:bg-brand-900/10 font-medium' : 'border-transparent text-gray-500 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-300' }}">
                                    <i data-lucide="{{ $tab['icon'] }}" class="w-4 h-4"></i>
                                    <span>{{ $tab['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <x-nav-link href="{{ route('prop-firm') }}" icon="trophy" :active="request()->routeIs('prop-firm')">Prop Firm</x-nav-link>
                    <x-nav-link href="{{ route('investments') }}" icon="trending-up" :active="request()->routeIs('investments')">Investments</x-nav-link>
                    <x-nav-link href="{{ route('loyalty') }}" icon="gift" :active="request()->routeIs('loyalty')">Loyalty Points</x-nav-link>
                    <x-nav-link href="{{ route('p2p') }}" icon="refresh-cw" :active="request()->routeIs('p2p')">P2P Exchange</x-nav-link>
                    <x-nav-link href="{{ route('ai-center') }}" icon="bot" :active="request()->routeIs('ai-center')">AI Center</x-nav-link>
                    <x-nav-link href="{{ route('download') }}" icon="download" :active="request()->routeIs('download')">Download Platform</x-nav-link>
                    
                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-800">
                        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System</div>
                        <x-nav-link href="{{ route('widgets') }}" icon="layout-grid" :active="request()->routeIs('widgets')">Widgets</x-nav-link>
                        <x-nav-link href="{{ route('support') }}" icon="life-buoy" :active="request()->routeIs('support')">Support Desk</x-nav-link>
                        <x-nav-link href="{{ route('profile') }}" icon="user" :active="request()->routeIs('profile')">Profile</x-nav-link>
                        <x-nav-link href="{{ route('settings') }}" icon="settings" :active="request()->routeIs('settings')">Settings</x-nav-link>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                <i data-lucide="log-out" class="w-5 h-5"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white dark:bg-dark-card border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-6 shrink-0 relative z-30 shadow-sm">
                 <div class="flex items-center gap-4 flex-1">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <!-- AI Assistant Quick Bar (Header Position - Updated with Border & Form) -->
                    <form action="{{ route('ai-center') }}" method="GET" class="hidden md:flex flex-1 max-w-xl mr-auto items-center bg-gray-50 dark:bg-gray-800 rounded-full px-4 py-2 border-2 border-brand-200 dark:border-brand-900 focus-within:border-brand-500 dark:focus-within:border-brand-500 transition-all shadow-sm">
                         <i data-lucide="bot" class="w-4 h-4 text-brand-600 dark:text-brand-400 mr-3"></i>
                         <input 
                           type="text" 
                           name="prompt"
                           placeholder="Ask Avi about markets, trends, or your account..." 
                           class="flex-1 bg-transparent border-none outline-none text-sm text-gray-900 dark:text-white placeholder-gray-500 w-full"
                         />
                         <button type="submit" class="text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 transition-colors p-1">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                         </button>
                    </form>
                </div>

                <div class="flex items-center gap-4">
                     <!-- Wallet Balance Display - Styled like KYC Badge (Green Text/Border) -->
                     <a href="{{ route('wallet') }}" class="hidden lg:flex items-center gap-2 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-xs font-bold mr-2 transition-all border border-emerald-600 dark:border-emerald-400">
                        <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                        <span class="font-mono text-sm">$24,592.50</span>
                     </a>

                     <!-- KYC Status - Flashing Indicator (Red for Pending, Green for Verified) -->
                    <a href="{{ route('kyc') }}" class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-bold mr-2 transition-all border border-red-200 dark:border-red-800">
                       <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                       </span>
                       <span>KYC Pending</span>
                    </a>

                    <!-- Language Selector -->
                    <button class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 text-sm font-medium mr-2">
                       <i data-lucide="globe" class="w-4 h-4"></i>
                       <span class="hidden sm:inline">EN</span>
                    </button>

                    <!-- Notifications -->
                    <button class="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-brand-500 to-purple-500 p-[2px]">
                        <img src="https://picsum.photos/100/100" class="rounded-full border-2 border-white dark:border-dark-card">
                    </div>
                </div>
            </header>
            
            <!-- Promotion Ticker (New Gradient Color) -->
            <div class="bg-gradient-to-r from-brand-900 via-blue-800 to-brand-900 text-white text-sm py-2 overflow-hidden relative z-20 shrink-0 border-b border-brand-800 shadow-md">
                <div class="flex animate-marquee whitespace-nowrap items-center">
                    <!-- Loop items -->
                    <div class="flex items-center mx-8">
                        <i data-lucide="megaphone" class="w-4 h-4 mr-2 text-yellow-400"></i>
                        <span class="font-medium tracking-wide">Special Offer: Get <span class="text-yellow-400 font-bold">100% Deposit Bonus</span> on your first funding!</span>
                    </div>
                    <span class="opacity-30 text-brand-300">•</span>
                    <div class="flex items-center mx-8">
                         <i data-lucide="trophy" class="w-4 h-4 mr-2 text-yellow-400"></i>
                         <span class="font-medium tracking-wide">New Feature: Prop Firm Challenges now up to <span class="text-white font-bold">$200k Funding</span>.</span>
                    </div>
                    <span class="opacity-30 text-brand-300">•</span>
                    <div class="flex items-center mx-8">
                         <i data-lucide="bot" class="w-4 h-4 mr-2 text-yellow-400"></i>
                         <span class="font-medium tracking-wide">AI Signals: Access <span class="text-white font-bold">GPT-4 Market Analysis</span> in AI Center.</span>
                    </div>
                    
                    <!-- Duplicates -->
                     <span class="opacity-30 text-brand-300">•</span>
                     <div class="flex items-center mx-8">
                        <i data-lucide="megaphone" class="w-4 h-4 mr-2 text-yellow-400"></i>
                        <span class="font-medium tracking-wide">Special Offer: Get <span class="text-yellow-400 font-bold">100% Deposit Bonus</span> on your first funding!</span>
                    </div>
                    <span class="opacity-30 text-brand-300">•</span>
                    <div class="flex items-center mx-8">
                         <i data-lucide="trophy" class="w-4 h-4 mr-2 text-yellow-400"></i>
                         <span class="font-medium tracking-wide">New Feature: Prop Firm Challenges now up to <span class="text-white font-bold">$200k Funding</span>.</span>
                    </div>
                </div>
            </div>
            
            <!-- Market Ticker -->
            <div class="bg-slate-950 border-b border-slate-800 overflow-hidden h-9 flex items-center relative z-20 shrink-0">
                <div class="flex animate-ticker whitespace-nowrap hover:[animation-play-state:paused]">
                    <!-- Loop for items -->
                    @php
                        $tickerItems = [
                            ['s' => 'EUR/USD', 'p' => '1.0832', 'c' => '+0.12%', 'u' => true],
                            ['s' => 'GBP/USD', 'p' => '1.2640', 'c' => '-0.05%', 'u' => false],
                            ['s' => 'USD/JPY', 'p' => '150.20', 'c' => '+0.45%', 'u' => true],
                            ['s' => 'XAU/USD', 'p' => '2,035.10', 'c' => '+1.20%', 'u' => true],
                            ['s' => 'XAG/USD', 'p' => '22.45', 'c' => '+0.80%', 'u' => true],
                            ['s' => 'US30', 'p' => '38,500', 'c' => '+0.50%', 'u' => true],
                            ['s' => 'NAS100', 'p' => '17,650', 'c' => '-0.20%', 'u' => false],
                            ['s' => 'BTC/USD', 'p' => '43,500', 'c' => '+2.10%', 'u' => true],
                            ['s' => 'ETH/USD', 'p' => '2,250', 'c' => '+1.80%', 'u' => true],
                            ['s' => 'SOL/USD', 'p' => '98.50', 'c' => '-1.50%', 'u' => false],
                        ];
                        // Double the items for seamless scrolling
                        $displayItems = array_merge($tickerItems, $tickerItems);
                    @endphp
                    
                    @foreach($displayItems as $item)
                        <div class="flex items-center gap-2 px-6 border-r border-slate-800/60 h-9">
                            <span class="font-bold text-xs text-slate-400">{{ $item['s'] }}</span>
                            <span class="font-mono text-xs text-white">{{ $item['p'] }}</span>
                            <span class="text-xs flex items-center {{ $item['u'] ? 'text-emerald-400' : 'text-rose-400' }}">
                                <i data-lucide="{{ $item['u'] ? 'arrow-up' : 'arrow-down' }}" class="w-3 h-3 mr-0.5"></i>
                                {{ $item['c'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-dark-bg flex flex-col">
                <div class="flex-1 p-6">
                    @yield('content')
                </div>

                <footer class="py-8 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-dark-bg shrink-0">
                   <div class="flex flex-col items-center justify-center gap-3">
                      <div class="flex items-center gap-2.5">
                         <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md bg-gradient-to-br from-brand-500 to-brand-700">
                            B
                         </div>
                         <span class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">
                            BridgeX Suite<sup class="text-xs text-gray-500 dark:text-gray-400 font-medium ml-0.5">TM</sup>
                         </span>
                      </div>
                      <div class="text-sm text-gray-500 dark:text-gray-400 flex flex-col sm:flex-row items-center gap-2">
                         <span>&copy; {{ date('Y') }} BridgeX Financial Systems.</span>
                         <span class="hidden sm:inline opacity-50">|</span>
                         <span>All Rights Reserved.</span>
                      </div>
                   </div>
                </footer>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
