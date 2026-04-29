<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 text-[#0d0d0d] dark:text-zinc-100 font-['Inter'] antialiased transition-colors duration-300">
        <div class="relative flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 overflow-hidden">
            {{-- Background glow effect from design --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[600px] bg-[radial-gradient(circle_at_center,_#d4fae8_0%,_transparent_70%)] dark:bg-[radial-gradient(circle_at_center,_#0fa76e22_0%,_transparent_70%)] opacity-40 -z-10"></div>
            
            <div class="flex w-full max-w-sm lg:max-w-4xl flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2" wire:navigate>
                    <span class="text-2xl font-bold tracking-tight text-[#0d0d0d] dark:text-white">TECH<span class="text-[#18E299]">SHOP</span></span>
                </a>
                
                <div class="relative z-10 flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
