<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>
        {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
    </title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-white text-[#0d0d0d] font-['Inter'] antialiased">
    <livewire:shop-navigation />

    <main class="pt-16">
        {{ $slot }}
    </main>

    <footer class="mt-24 border-t border-black/[0.05] bg-white">
        <div class="max-w-[1200px] mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="md:col-span-2">
                <a href="{{ route('home') }}" class="text-xl font-semibold tracking-tight" wire:navigate>
                    TECH<span class="text-[#18E299]">SHOP</span>
                </a>
                <p class="mt-4 text-[14px] text-[#666666] max-w-sm leading-relaxed">
                    {{ __('High-performance hardware engineered for developers, designers, and digital architects.') }}
                </p>
            </div>

            <div>
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-4">
                    {{ __('Shop') }}
                </div>
                <ul class="space-y-2 text-[14px]">
                    <li><a href="/products" class="hover:text-[#18E299] transition-colors">{{ __('All Products') }}</a></li>
                    <li><a href="/products" class="hover:text-[#18E299] transition-colors">{{ __('New Arrivals') }}</a></li>
                </ul>
            </div>

            <div>
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-4">
                    {{ __('Account') }}
                </div>
                <ul class="space-y-2 text-[14px]">
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="hover:text-[#18E299] transition-colors" wire:navigate>{{ __('Dashboard') }}</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-[#18E299] transition-colors" wire:navigate>{{ __('Log in') }}</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-[#18E299] transition-colors" wire:navigate>{{ __('Register') }}</a></li>
                    @endauth
                </ul>
            </div>
        </div>

        <div class="border-t border-black/[0.05]">
            <div class="max-w-[1200px] mx-auto px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-[12px] text-[#666666]">
                <div>&copy; {{ date('Y') }} {{ config('app.name', 'TechShop') }}. {{ __('All rights reserved.') }}</div>
                <div class="font-mono tracking-widest uppercase">{{ __('Built for creators') }}</div>
            </div>
        </div>
    </footer>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
