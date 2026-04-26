<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechShop | Premium Developer Gear</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @livewireStyles
</head>
<body class="bg-white text-[#0d0d0d] font-['Inter'] antialiased">

    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-black/[0.05]">
        <div class="max-w-[1200px] mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="/" class="text-xl font-semibold tracking-tight">TECH<span class="text-[#18E299]">SHOP</span></a>
                <div class="hidden md:flex gap-6 text-[14px] font-medium">
                    <a href="/products" class="hover:text-[#18E299] transition-colors">Catalogus</a>
                    <a href="#" class="hover:text-[#18E299] transition-colors">Workstations</a>
                    <a href="#" class="hover:text-[#18E299] transition-colors">Peripherals</a>
                </div>
            </div>
            
            <div class="flex flex-row items-center gap-6">
                <!-- Cart Link -->
                <a href="/cart" class="text-[14px] font-medium hover:text-[#18E299] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Winkelwagen
                </a>

                @auth
                    <a href="/dashboard" class="bg-[#0d0d0d] text-white px-5 py-2 rounded-full text-[14px] font-medium hover:opacity-90 transition-all shadow-sm">Dashboard</a>
                @else
                    <a href="/login" class="bg-[#0d0d0d] text-white px-5 py-2 rounded-full text-[14px] font-medium hover:opacity-90 transition-all shadow-sm">Log in</a>
                @endauth
            </div>
        </div>
    </nav>

    {{ $slot }}

    @livewireScripts
</body>
</html>
