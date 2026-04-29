function applyTheme() {
    // Use a custom key to avoid conflicts with other libraries
    const theme = localStorage.getItem('techshop.theme') || localStorage.getItem('flux.appearance');
    
    const isDark = theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches);
    
    if (isDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Sync back to flux key for compatibility
    if (theme) {
        localStorage.setItem('flux.appearance', theme);
        document.cookie = `flux.appearance=${theme}; path=/; max-age=31536000; SameSite=Lax`;
    }
}

// Initial execution
applyTheme();

// Handle Livewire navigation
document.addEventListener('livewire:navigated', () => {
    applyTheme();
    setTimeout(applyTheme, 50);
});

// Global helper for toggles
window.techshopToggleTheme = function(currentIsDark) {
    const newIsDark = !currentIsDark;
    const mode = newIsDark ? 'dark' : 'light';
    localStorage.setItem('techshop.theme', mode);
    applyTheme();
    return newIsDark;
}
