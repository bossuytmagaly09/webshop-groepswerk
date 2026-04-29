<footer class="mt-24 border-t border-black/[0.05] dark:border-white/[0.05] bg-white dark:bg-zinc-950">
    <div class="max-w-[1200px] mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-5 gap-10">
        {{-- Logo & tagline --}}
        <div class="md:col-span-2">
            <a href="{{ route('home') }}" class="text-xl font-semibold tracking-tight dark:text-white" wire:navigate>
                TECH<span class="text-[#18E299]">SHOP</span>
            </a>
            <p class="mt-4 text-[14px] text-[#666666] dark:text-zinc-400 max-w-sm leading-relaxed">
                {{ __('High-performance hardware engineered for developers, designers, and digital architects.') }}
            </p>
        </div>

        {{-- Shop --}}
        <div>
            <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-4">
                {{ __('Shop') }}
            </div>
            <ul class="space-y-2 text-[14px]">
                <li><a href="{{ route('products') }}" class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors" wire:navigate>{{ __('All Products') }}</a></li>
                <li>
                    <a
                        x-data
                        href="{{ route('home') }}#new-arrivals"
                        @click.prevent="
                            const homePath = new URL('{{ route('home') }}').pathname;
                            if (window.location.pathname === homePath) {
                                document.getElementById('new-arrivals').scrollIntoView({behavior: 'smooth'});
                            } else {
                                window.location.href = '{{ route('home') }}#new-arrivals';
                            }
                        "
                        class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors"
                    >{{ __('New Arrivals') }}</a>
                </li>
                <li>
                    <a
                        x-data
                        href="{{ route('home') }}#categories"
                        @click.prevent="
                            const homePath = new URL('{{ route('home') }}').pathname;
                            if (window.location.pathname === homePath) {
                                document.getElementById('categories').scrollIntoView({behavior: 'smooth'});
                            } else {
                                window.location.href = '{{ route('home') }}#categories';
                            }
                        "
                        class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors"
                    >{{ __('Categories') }}</a>
                </li>
            </ul>
        </div>

        {{-- Account --}}
        <div>
            <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-4">
                {{ __('Account') }}
            </div>
            <ul class="space-y-2 text-[14px]">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('dashboard') }}" class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors" wire:navigate>{{ __('Dashboard') }}</a></li>
                    @else
                        <li><a href="#" class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors">{{ __('Edit Profile') }}</a></li>
                    @endif
                @else
                    <li><a href="{{ route('login') }}" class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors" wire:navigate>{{ __('Log in') }}</a></li>
                    <li><a href="{{ route('register') }}" class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors" wire:navigate>{{ __('Register') }}</a></li>
                @endauth
            </ul>
        </div>

        {{-- Support --}}
        <div>
            <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-4">
                {{ __('Support') }}
            </div>
            <ul class="space-y-2 text-[14px]">
                <li><a href="{{ route('contact') }}" class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors" wire:navigate>{{ __('Contact') }}</a></li>
                <li><a href="{{ route('faq') }}" class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors" wire:navigate>{{ __('FAQ') }}</a></li>
                <li><a href="{{ route('shipping-returns') }}" class="text-[#0d0d0d] dark:text-zinc-300 hover:text-[#18E299] transition-colors" wire:navigate>{{ __('Shipping & Returns') }}</a></li>
            </ul>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-black/[0.05] dark:border-white/[0.05]">
        <div class="max-w-[1200px] mx-auto px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-[12px] text-[#666666] dark:text-zinc-400">
            <div>&copy; {{ date('Y') }} TechShop. {{ __('All rights reserved.') }}</div>

            {{-- Social icons --}}
            <div class="flex items-center gap-4">
                {{-- GitHub --}}
                <a href="https://github.com" target="_blank" rel="noopener noreferrer" aria-label="GitHub" class="text-[#999999] dark:text-zinc-500 hover:text-[#18E299] dark:hover:text-[#18E299] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/>
                        <path d="M9 18c-4.51 2-5-2-7-2"/>
                    </svg>
                </a>

                {{-- Twitter / X --}}
                <a href="https://x.com" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X" class="text-[#999999] dark:text-zinc-500 hover:text-[#18E299] dark:hover:text-[#18E299] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.259 5.632zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>

                {{-- Instagram --}}
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="text-[#999999] dark:text-zinc-500 hover:text-[#18E299] dark:hover:text-[#18E299] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                    </svg>
                </a>
            </div>

            <div class="font-mono tracking-widest uppercase">{{ __('Built for creators') }}</div>
        </div>
    </div>
</footer>
