<?php

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function categories(): Collection
    {
        return Category::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }
}; ?>

<nav
    x-data="{ open: false }"
    class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-black/[0.05]"
>
    <div class="max-w-[1200px] mx-auto px-6 h-16 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-xl font-semibold tracking-tight" wire:navigate>
                TECH<span class="text-[#18E299]">SHOP</span>
            </a>

            <div class="hidden md:flex items-center gap-6 text-[14px] font-medium">
                <a href="/products" class="hover:text-[#18E299] transition-colors">
                    {{ __('Products') }}
                </a>

                @foreach ($this->categories->take(3) as $category)
                    <a
                        wire:key="cat-{{ $category->id }}"
                        href="/categories/{{ $category->slug }}"
                        class="hover:text-[#18E299] transition-colors"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a
                href="/products"
                class="hidden sm:inline-flex bg-[#0d0d0d] text-white px-6 py-2 rounded-full text-[15px] font-medium hover:opacity-90 transition-all shadow-sm"
            >
                {{ __('Shop Now') }}
            </a>

            <button
                type="button"
                @click="open = ! open"
                class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-full border border-black/[0.08] hover:bg-gray-50 transition-colors"
                :aria-expanded="open"
                aria-controls="shop-mobile-menu"
                aria-label="{{ __('Toggle menu') }}"
            >
                <svg x-show="! open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

    <div
        id="shop-mobile-menu"
        x-show="open"
        x-cloak
        x-transition.opacity
        @click.outside="open = false"
        class="md:hidden border-t border-black/[0.05] bg-white"
    >
        <div class="max-w-[1200px] mx-auto px-6 py-4 flex flex-col gap-1 text-[15px] font-medium">
            <a href="/products" class="py-2 hover:text-[#18E299] transition-colors">
                {{ __('Products') }}
            </a>

            <div class="pt-3 mt-2 border-t border-black/[0.05]">
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-2">
                    {{ __('Categories') }}
                </div>

                @forelse ($this->categories as $category)
                    <a
                        wire:key="m-cat-{{ $category->id }}"
                        href="/categories/{{ $category->slug }}"
                        class="block py-2 hover:text-[#18E299] transition-colors"
                    >
                        {{ $category->name }}
                    </a>
                @empty
                    <span class="block py-2 text-[#666666] text-[14px]">
                        {{ __('Geen categorieën beschikbaar.') }}
                    </span>
                @endforelse
            </div>

            <a
                href="/products"
                class="mt-4 inline-flex justify-center bg-[#0d0d0d] text-white px-6 py-3 rounded-full text-[15px] font-medium hover:opacity-90 transition-all shadow-sm"
            >
                {{ __('Shop Now') }}
            </a>
        </div>
    </div>
</nav>
