@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Prop Firm Challenges</h1>
            <p class="text-gray-500">Prove your skills and get funded.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($challenges as $plan)
        <div class="relative p-6 rounded-2xl border {{ $plan->active ? 'border-brand-500 ring-2 ring-brand-500/20 bg-brand-50 dark:bg-brand-900/10' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-dark-card' }}">
            @if($plan->active)
            <div class="absolute top-0 right-0 bg-brand-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl rounded-tr-xl">POPULAR</div>
            @endif
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">${{ $plan->size }} <span class="text-lg font-normal text-gray-500">Account</span></h3>
            <div class="text-2xl font-bold text-brand-600 dark:text-brand-400 mb-6">{{ $plan->price }}</div>
            <button class="w-full py-3 rounded-xl font-bold transition-colors {{ $plan->active ? 'bg-brand-600 text-white' : 'bg-gray-100 dark:bg-gray-800' }}">Start Challenge</button>
        </div>
        @endforeach
    </div>
</div>
@endsection
