<?php

use Livewire\Component;

new class extends Component {};
?>

<nav
    x-data="{ open: false, accountOpen: false }"
    class="fixed top-0 inset-x-0 z-50 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md border-b border-black/[0.05] dark:border-white/[0.05]"
>
    <div class="max-w-[1200px] mx-auto px-6 h-16 flex items-center justify-between">
        {{-- Logo --}}
        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-xl font-semibold tracking-tight" wire:navigate>
                TECH<span class="text-[#18E299]">SHOP</span>
            </a>

            {{-- Desktop nav links --}}
            <div class="hidden md:flex items-center gap-6 text-[14px] font-medium">
                <a href="{{ route('products') }}" class="hover:text-[#18E299] transition-colors" wire:navigate>
                    {{ __('Shop') }}
                </a>
                @auth
                    <a href="{{ route('my-orders') }}" class="hover:text-[#18E299] transition-colors" wire:navigate>
                        {{ __('My Orders') }}
                    </a>
                @endauth
            </div>
        </div>

        {{-- Right side icons --}}
        <div class="flex items-center gap-1">
            {{-- Dark mode toggle --}}
            <button
                type="button"
                x-data="{ dark: document.documentElement.classList.contains('dark') }"
                @click="dark = window.techshopToggleTheme(dark)"
                class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors"
                aria-label="{{ __('Toggle dark mode') }}"
            >
                <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                </svg>
                <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                </svg>
            </button>

            @auth
                <div class="hidden sm:flex items-center px-1">
                    <span class="text-[13px] font-medium text-[#666666] dark:text-zinc-400">
                        {{ auth()->user()->name }}
                    </span>
                </div>
            @endauth

            {{-- Account icon --}}
            <div class="relative">
                @auth
                    <button
                        type="button"
                        @click="accountOpen = !accountOpen"
                        @click.outside="accountOpen = false"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors"
                        aria-label="{{ __('Account menu') }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        </svg>
                    </button>

                    <div
                        x-show="accountOpen"
                        x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-44 bg-white dark:bg-zinc-900 rounded-xl border border-black/[0.07] dark:border-white/[0.07] shadow-lg py-1 z-50"
                    >
                        @if(auth()->user()->role === 'admin')
                            <a
                                href="{{ route('dashboard') }}"
                                wire:navigate
                                @click="accountOpen = false"
                                class="flex items-center gap-2 px-4 py-2.5 text-[14px] hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors"
                            >
                                {{ __('Dashboard') }}
                            </a>
                        @else
                            <a
                                href="{{ route('profile.edit') }}"
                                wire:navigate
                                @click="accountOpen = false"
                                class="flex items-center gap-2 px-4 py-2.5 text-[14px] hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors"
                            >
                                {{ __('Edit Profile') }}
                            </a>
                        @endif
                        <div class="my-1 border-t border-black/[0.05] dark:border-white/[0.05]"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-[14px] hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors text-red-500">
                                {{ __('Log out') }}
                            </button>
                        </form>
                    </div>
                @else
                    <a
                        href="{{ route('login') }}"
                        wire:navigate
                        class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors"
                        aria-label="{{ __('Log in') }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        </svg>
                    </a>
                @endauth
            </div>

            {{-- Cart icon (Livewire component) --}}
            <livewire:cart-icon />

            {{-- Hamburger (mobile only) --}}
            <button
                type="button"
                @click="open = !open"
                class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-full border border-black/[0.08] dark:border-white/[0.08] hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors ml-1"
                :aria-expanded="open"
                aria-controls="shop-mobile-menu"
                aria-label="{{ __('Toggle menu') }}"
            >
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="7" x2="20" y2="7"/>
                    <line x1="4" y1="12" x2="20" y2="12"/>
                    <line x1="4" y1="17" x2="20" y2="17"/>
                </svg>
                <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="6" y1="6" x2="18" y2="18"/>
                    <line x1="6" y1="18" x2="18" y2="6"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div
        id="shop-mobile-menu"
        x-show="open"
        x-cloak
        x-transition.opacity
        @click.outside="open = false"
        class="md:hidden border-t border-black/[0.05] dark:border-white/[0.05] bg-white dark:bg-zinc-950"
    >
        <div class="max-w-[1200px] mx-auto px-6 py-4 flex flex-col gap-1 text-[15px] font-medium">
            <a href="{{ route('products') }}" wire:navigate @click="open = false" class="py-2 hover:text-[#18E299] transition-colors">
                {{ __('Shop') }}
            </a>

            @auth
                <a href="{{ route('my-orders') }}" wire:navigate @click="open = false" class="py-2 hover:text-[#18E299] transition-colors">
                    {{ __('My Orders') }}
                </a>
            @endauth

            @auth
                <div class="pt-3 mt-2 border-t border-black/[0.05] dark:border-white/[0.05] flex flex-col gap-1">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" wire:navigate @click="open = false" class="py-2 hover:text-[#18E299] transition-colors">
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('profile.edit') }}" wire:navigate @click="open = false" class="py-2 hover:text-[#18E299] transition-colors">
                            {{ __('Edit Profile') }}
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="py-2 text-red-500 hover:opacity-80 transition-opacity text-left w-full">
                            {{ __('Log out') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="pt-3 mt-2 border-t border-black/[0.05] dark:border-white/[0.05]">
                    <a href="{{ route('login') }}" wire:navigate @click="open = false" class="py-2 hover:text-[#18E299] transition-colors block">
                        {{ __('Log in') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
