<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? config('app.name', 'TechShop').' - '.$title : config('app.name', 'TechShop') }}
</title>

<link rel="icon" href="/favicon.svg?v={{ time() }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<style>
    html { background-color: #f9f9f9; }
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
