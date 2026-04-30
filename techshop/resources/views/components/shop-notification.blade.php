<div
    x-data="{ 
        show: false, 
        message: '', 
        title: '',
        link: null,
        linkText: null,
        timeout: null,
        notify(event) {
            this.title = event.detail.title || '{{ __('Success') }}';
            this.message = event.detail.message || '';
            this.link = event.detail.link || null;
            this.linkText = event.detail.linkText || null;
            this.show = true;
            
            if (this.timeout) clearTimeout(this.timeout);
            this.timeout = setTimeout(() => { this.show = false }, 5000);
        }
    }"
    @notify.window="notify($event)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-20 right-6 z-[100] max-w-sm w-full bg-white dark:bg-zinc-900 border border-black/[0.08] dark:border-white/[0.08] rounded-[20px] shadow-2xl shadow-black/10 p-4 overflow-hidden"
    x-cloak
>
    <div class="flex items-start gap-4">
        <div class="size-10 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
        
        <div class="flex-1 min-w-0">
            <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-1" x-text="title"></div>
            <div class="text-[14px] font-medium text-[#0d0d0d] dark:text-zinc-50 leading-snug mb-3" x-text="message"></div>
            
            <template x-if="link">
                <a 
                    :href="link" 
                    wire:navigate
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#0fa76e] dark:bg-[#18E299] text-white dark:text-[#0d0d0d] text-[11px] font-bold uppercase tracking-wider hover:opacity-90 transition-opacity"
                >
                    <span x-text="linkText"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </template>
        </div>

        <button @click="show = false" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    </div>

    {{-- Decorative accent line --}}
    <div class="absolute bottom-0 left-0 h-1 bg-[#0fa76e] dark:bg-[#18E299] transition-all duration-5000 ease-linear" :style="show ? 'width: 100%' : 'width: 0%'" x-show="show"></div>
</div>
