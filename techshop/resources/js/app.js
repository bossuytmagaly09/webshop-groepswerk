function applyTheme() {
    // Check both the custom key and the flux key
    const theme = localStorage.getItem('techshop.theme') || localStorage.getItem('flux.appearance');

    const isDark = theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (isDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Always keep flux.appearance in sync for Flux components
    if (theme) {
        localStorage.setItem('flux.appearance', theme);
        document.cookie = `flux.appearance=${theme}; path=/; max-age=31536000; SameSite=Lax`;
    }
}

// Initial run
applyTheme();

// Run after Livewire navigation
document.addEventListener('livewire:navigated', () => {
    applyTheme();
});

// Expose a global function for the toggle buttons
window.techshopToggleTheme = function(currentIsDark) {
    const newIsDark = !currentIsDark;
    const mode = newIsDark ? 'dark' : 'light';
    localStorage.setItem('techshop.theme', mode);
    applyTheme();
    return newIsDark;
}
