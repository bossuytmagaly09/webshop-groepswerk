<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>
        {{ filled($title ?? null) ? config('app.name', 'TechShop').' - '.$title : config('app.name', 'TechShop') }}
    </title>

    <link rel="icon" href="/favicon.svg?v={{ time() }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        html { background-color: #ffffff; }
        html.dark { background-color: #09090b; }
        body { background-color: transparent; }
        [x-cloak] { display: none !important; }
    </style>

    <script>
        (function() {
            const theme = localStorage.getItem('techshop.theme') || localStorage.getItem('flux.appearance');
            const isDark = theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-white dark:bg-zinc-950 text-[#0d0d0d] dark:text-zinc-50 font-['Inter'] antialiased">
    <livewire:shop-navigation />

    <main class="pt-16">
        {{ $slot }}
    </main>

    <x-shop-notification />
    <x-shop-footer />

    @fluxScripts
</body>
</html>
