<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? config('app.name', 'TechShop').' - '.$title : config('app.name', 'TechShop') }}
</title>

<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<style>
    :root { background-color: #f9f9f9; }
    :root.dark { background-color: #09090b; }
    [x-cloak] { display: none !important; }
</style>

<script>
    const theme = localStorage.getItem('techshop.theme') || localStorage.getItem('flux.appearance');
    if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
