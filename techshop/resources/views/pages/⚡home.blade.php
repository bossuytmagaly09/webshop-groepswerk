<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('TechShop'), Layout('layouts.shop')] class extends Component
{
    #[Computed]
    public function categories(): Collection
    {
        return Category::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }

    #[Computed]
    public function newestProducts(): Collection
    {
        return Product::query()
            ->with('category')
            ->available()
            ->newest(4)
            ->get();
    }

    #[Computed]
    public function featuredProduct(): ?Product
    {
        return Product::query()
            ->with('category')
            ->available()
            ->newest(1)
            ->first();
    }
}; ?>

<div>
    {{-- 1. Hero — split layout --}}
    <section class="relative pt-16 pb-20 overflow-hidden">
        <div class="absolute top-0 right-0 w-[700px] h-[700px] bg-[radial-gradient(circle_at_center,_#d4fae8_0%,_transparent_70%)] opacity-50 -z-10"></div>

        <div class="max-w-[1200px] mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Left: copy --}}
            <div>
                <div class="inline-block bg-[#d4fae8] text-[#0fa76e] text-[12px] font-bold tracking-[0.6px] uppercase px-3 py-1 rounded-full mb-6">
                    {{ __('In stock — ships today') }}
                </div>

                <h1 class="text-5xl md:text-[64px] font-semibold leading-[1.1] tracking-[-1.28px] mb-6">
                    {{ __('Hardware that gets out of your way.') }}
                </h1>

                <p class="text-lg text-[#666666] max-w-xl mb-10 leading-relaxed">
                    {{ __('High-performance gear engineered for developers, designers, and digital architects who care about the details.') }}
                </p>

                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('products') }}" class="bg-[#0d0d0d] text-white px-8 py-3 rounded-full text-[15px] font-medium shadow-md hover:opacity-90 transition-all" wire:navigate>
                        {{ __('Browse Collection') }}
                    </a>
                    <a href="#new-arrivals" class="text-[14px] font-medium text-[#0d0d0d] hover:text-[#0fa76e] transition-colors inline-flex items-center gap-1">
                        {{ __('View new arrivals') }}
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>

            {{-- Right: featured product visual --}}
            <div class="relative">
                <div class="absolute -inset-6 bg-gradient-to-tr from-[#18E299]/30 via-[#d4fae8]/40 to-transparent rounded-[32px] blur-2xl -z-10"></div>

                @if ($this->featuredProduct)
                    <a href="/products/{{ $this->featuredProduct->slug }}" class="group block relative bg-white rounded-[24px] border border-black/[0.06] shadow-sm overflow-hidden">
                        <div class="aspect-[4/5] bg-gradient-to-br from-[#fafafa] to-[#f0fdf4] relative">
                            <div class="absolute inset-0 flex items-center justify-center font-mono text-[11px] text-gray-300 uppercase tracking-widest">
                                {{ __('No image') }}
                            </div>

                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-[10px] font-mono text-[#0fa76e] tracking-widest uppercase px-2.5 py-1 rounded-full border border-black/[0.05]">
                                {{ __('Featured') }}
                            </div>
                        </div>

                        <div class="p-6 border-t border-black/[0.05]">
                            <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-1">
                                {{ $this->featuredProduct->category?->name ?? '—' }}
                            </div>
                            <h3 class="text-[18px] font-medium mb-1 group-hover:text-[#18E299] transition-colors">
                                {{ $this->featuredProduct->name }}
                            </h3>
                            <div class="text-[16px] font-semibold">
                                {{ $this->featuredProduct->formatted_price }}
                            </div>
                        </div>
                    </a>
                @else
                    <div class="aspect-[4/5] bg-gradient-to-br from-[#fafafa] to-[#d4fae8] rounded-[24px] border border-black/[0.06] flex items-center justify-center">
                        <span class="font-mono text-[11px] text-[#0fa76e] tracking-widest uppercase">{{ __('TechShop') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- 2. USP bar --}}
    <section class="border-y border-black/[0.05] bg-[#fafafa]/60">
        <div class="max-w-[1200px] mx-auto px-6 py-8 grid grid-cols-2 md:grid-cols-4 gap-6">
            {{-- Free shipping --}}
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[#d4fae8] text-[#0fa76e] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                        <path d="M15 18H9"/>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                        <circle cx="17" cy="18" r="2"/>
                        <circle cx="7" cy="18" r="2"/>
                    </svg>
                </span>
                <div>
                    <div class="text-[13px] font-medium leading-tight">{{ __('Free shipping') }}</div>
                    <div class="text-[12px] text-[#666666] leading-tight">{{ __('On orders over €50') }}</div>
                </div>
            </div>

            {{-- Warranty --}}
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[#d4fae8] text-[#0fa76e] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                    </svg>
                </span>
                <div>
                    <div class="text-[13px] font-medium leading-tight">{{ __('2-year warranty') }}</div>
                    <div class="text-[12px] text-[#666666] leading-tight">{{ __('On every product') }}</div>
                </div>
            </div>

            {{-- Secure checkout --}}
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[#d4fae8] text-[#0fa76e] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <div>
                    <div class="text-[13px] font-medium leading-tight">{{ __('Secure checkout') }}</div>
                    <div class="text-[12px] text-[#666666] leading-tight">{{ __('Powered by Stripe') }}</div>
                </div>
            </div>

            {{-- Built for creators --}}
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[#d4fae8] text-[#0fa76e] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/>
                    </svg>
                </span>
                <div>
                    <div class="text-[13px] font-medium leading-tight">{{ __('Built for creators') }}</div>
                    <div class="text-[12px] text-[#666666] leading-tight">{{ __('Picked by makers') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Categories --}}
    <section id="categories" class="pt-20 pb-16 max-w-[1200px] mx-auto px-6 scroll-mt-20">
        <div class="mb-10">
            <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-3">
                {{ __('Shop by category') }}
            </div>
            <h2 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px]">
                {{ __('Find your kit.') }}
            </h2>
        </div>

        @if ($this->categories->isEmpty())
            <p class="text-[#666666] text-[15px]">{{ __('No categories yet.') }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($this->categories as $category)
                    <a
                        wire:key="category-{{ $category->id }}"
                        href="{{ route('products') }}?category={{ $category->slug }}"
                        class="group relative block aspect-[4/5] rounded-[16px] border border-black/[0.05] overflow-hidden bg-gradient-to-br from-[#fafafa] to-[#f0fdf4] hover:border-[#18E299]/30 transition-all"
                        wire:navigate
                    >
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#18E299]/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="absolute top-5 left-5 right-5">
                            <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase">
                                {{ __('Category') }}
                            </div>
                        </div>

                        <div class="absolute bottom-5 left-5 right-5 flex items-end justify-between gap-3">
                            <h3 class="text-[20px] font-medium tracking-[-0.4px] group-hover:text-[#0fa76e] transition-colors">
                                {{ $category->name }}
                            </h3>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-black/[0.06] shrink-0 group-hover:bg-[#0d0d0d] group-hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"/>
                                    <path d="m12 5 7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    {{-- 4. New arrivals --}}
    <section id="new-arrivals" class="pt-16 pb-24 max-w-[1200px] mx-auto px-6 scroll-mt-20">
        <div class="mb-10 flex items-end justify-between gap-6">
            <div>
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-3">
                    {{ __('New arrivals') }}
                </div>
                <h2 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px]">
                    {{ __('Just landed.') }}
                </h2>
            </div>
            <a href="{{ route('products') }}" class="hidden sm:inline-flex items-center gap-1 text-[14px] font-medium text-[#0d0d0d] hover:text-[#0fa76e] transition-colors" wire:navigate>
                {{ __('View all') }}
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        @if ($this->newestProducts->isEmpty())
            <p class="text-[#666666] text-[15px]">{{ __('No products available yet.') }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($this->newestProducts as $product)
                    <x-product-card
                        wire:key="new-product-{{ $product->id }}"
                        :product="$product"
                    />
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                <a href="{{ route('products') }}" class="bg-white border border-black/[0.08] text-[#0d0d0d] px-8 py-3 rounded-full text-[15px] font-medium hover:bg-gray-50 transition-all" wire:navigate>
                    {{ __('View all products') }}
                </a>
            </div>
        @endif
    </section>
</div>
