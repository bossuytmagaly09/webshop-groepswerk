<?php

use App\Actions\Cart\AddItemToCartAction;
use App\Models\Product;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts.shop')] class extends Component
{
    public Product $product;

    #[Validate]
    public int $quantity = 1;

    public function mount(Product $product): void
    {
        $this->product = $product->load('category');
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:'.max($this->product->stock, 1)],
        ];
    }

    #[Computed]
    public function pageTitle(): string
    {
        return $this->product->name.' - '.config('app.name');
    }

    public function increment(): void
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrement(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(AddItemToCartAction $action): void
    {
        if ($this->product->stock <= 0) {
            return;
        }

        $this->validate();

        $action->execute($this->product, $this->quantity);

        $this->dispatch('cart-updated');

        Flux::toast(
            heading: __('Added to cart'),
            text: $this->product->name,
            variant: 'success',
        );
    }
}; ?>

<div>
    @php
        $stock = (int) $this->product->stock;
        $imageUrl = $this->product->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->product->image) : null;
    @endphp

    <title>{{ $this->pageTitle }}</title>

    <section class="max-w-[1200px] mx-auto px-6 pt-10 pb-24">
        {{-- Breadcrumb / back --}}
        <nav class="mb-8 text-[13px] text-[#666666] dark:text-zinc-400 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#0fa76e] dark:hover:text-[#18E299] transition-colors" wire:navigate>
                {{ __('Home') }}
            </a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('products') }}" class="hover:text-[#0fa76e] dark:hover:text-[#18E299] transition-colors" wire:navigate>
                {{ __('Products') }}
            </a>
            <span aria-hidden="true">/</span>
            <span class="text-[#0d0d0d] dark:text-zinc-200 truncate">{{ $this->product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
            {{-- Left: image --}}
            <div class="relative">
                <div class="absolute -inset-6 bg-gradient-to-tr from-[#18E299]/15 via-[#d4fae8]/20 to-transparent dark:from-[#18E299]/10 dark:via-[#18E299]/5 dark:to-transparent rounded-[32px] blur-xl -z-10"></div>

                <div class="aspect-square bg-[#fafafa] dark:bg-zinc-900 rounded-[24px] border border-black/[0.05] dark:border-white/[0.05] overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#18E299]/5 to-transparent"></div>

                    @if ($imageUrl)
                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $this->product->name }}"
                            class="absolute inset-0 w-full h-full object-cover"
                        />
                    @else
                        <div class="w-full h-full flex items-center justify-center font-mono text-[10px] text-gray-300 dark:text-zinc-700 uppercase tracking-widest">
                            {{ __('No image') }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right: info --}}
            <div class="flex flex-col">
                {{-- Category eyebrow --}}
                @if ($this->product->category)
                    <a
                        href="{{ route('products') }}?category={{ $this->product->category->slug }}"
                        class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-4 hover:underline self-start"
                        wire:navigate
                    >
                        {{ $this->product->category->name }}
                    </a>
                @endif

                {{-- Product name --}}
                <h1 class="text-4xl md:text-5xl font-semibold leading-[1.1] tracking-[-1px] mb-6 text-[#0d0d0d] dark:text-zinc-50">
                    {{ $this->product->name }}
                </h1>

                {{-- Price --}}
                <div class="text-[28px] font-semibold text-[#0d0d0d] dark:text-white mb-5">
                    {{ $this->product->formatted_price }}
                </div>

                {{-- Stock indicator --}}
                <div class="mb-6">
                    @if ($stock === 0)
                        <div class="inline-flex items-center gap-2 text-[13px] font-medium text-[#999999] dark:text-zinc-400">
                            <span class="w-2 h-2 rounded-full bg-[#999999] dark:bg-zinc-500"></span>
                            {{ __('Out of stock') }}
                        </div>
                    @elseif ($stock <= 5)
                        <div class="inline-flex items-center gap-2 text-[13px] font-medium text-[#b45309] dark:text-[#fcd34d]">
                            <span class="w-2 h-2 rounded-full bg-[#f59e0b] dark:bg-[#fcd34d]"></span>
                            {{ __('Low stock — only :count left', ['count' => $stock]) }}
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 text-[13px] font-medium text-[#0fa76e] dark:text-[#18E299]">
                            <span class="w-2 h-2 rounded-full bg-[#0fa76e] dark:bg-[#18E299]"></span>
                            {{ __('In stock') }}
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                @if (filled($this->product->description))
                    <p class="text-[15px] leading-relaxed text-[#666666] dark:text-zinc-400 mb-8">
                        {{ $this->product->description }}
                    </p>
                @endif

                {{-- Quantity selector + add to cart --}}
                <div class="flex flex-col gap-4 mt-2">
                    @if ($stock > 0)
                        <div class="flex items-center gap-4">
                            <span class="text-[13px] font-medium text-[#0d0d0d] dark:text-zinc-200">
                                {{ __('Quantity') }}
                            </span>
                            <div class="inline-flex items-center bg-white dark:bg-zinc-900 border border-black/[0.08] dark:border-white/[0.08] rounded-full overflow-hidden">
                                <button
                                    type="button"
                                    wire:click="decrement"
                                    @disabled($this->quantity <= 1)
                                    class="w-9 h-9 flex items-center justify-center text-[#0d0d0d] dark:text-zinc-200 hover:bg-gray-50 dark:hover:bg-zinc-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                                    aria-label="{{ __('Decrease quantity') }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/>
                                    </svg>
                                </button>
                                <span class="min-w-[40px] text-center text-[14px] font-medium text-[#0d0d0d] dark:text-zinc-100 tabular-nums">
                                    {{ $this->quantity }}
                                </span>
                                <button
                                    type="button"
                                    wire:click="increment"
                                    @disabled($this->quantity >= $stock)
                                    class="w-9 h-9 flex items-center justify-center text-[#0d0d0d] dark:text-zinc-200 hover:bg-gray-50 dark:hover:bg-zinc-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                                    aria-label="{{ __('Increase quantity') }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/>
                                        <path d="M12 5v14"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('quantity')
                            <div class="text-[12px] text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    @endif

                    <button
                        type="button"
                        wire:click="addToCart"
                        wire:loading.attr="disabled"
                        wire:target="addToCart"
                        @disabled($stock === 0)
                        class="w-full bg-[#0d0d0d] dark:bg-white text-white dark:text-[#0d0d0d] px-8 py-3.5 rounded-full text-[15px] font-medium shadow-md dark:shadow-none hover:opacity-90 transition-all disabled:opacity-40 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2"
                    >
                        <span wire:loading.remove wire:target="addToCart" class="inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1"/>
                                <circle cx="19" cy="21" r="1"/>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                            </svg>
                            {{ $stock === 0 ? __('Out of Stock') : __('Add to Cart') }}
                        </span>
                        <span wire:loading wire:target="addToCart">{{ __('Adding…') }}</span>
                    </button>

                    <a
                        href="{{ route('products') }}"
                        class="text-[13px] text-[#666666] dark:text-zinc-400 hover:text-[#0fa76e] dark:hover:text-[#18E299] transition-colors inline-flex items-center gap-1 self-start"
                        wire:navigate
                    >
                        <span aria-hidden="true">&larr;</span>
                        {{ __('Back to all products') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
