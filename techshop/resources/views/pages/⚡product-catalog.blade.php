<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Products'), Layout('layouts.shop')] class extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $category = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->with('category')
            ->search($this->search)
            ->inCategory($this->category)
            ->orderBy('name')
            ->paginate(12);
    }

    #[Computed]
    public function categories(): Collection
    {
        return Category::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }
}; ?>

<div>
    <section class="pt-12 pb-24 max-w-[1200px] mx-auto px-6">

        {{-- Page header --}}
        <div class="mb-10">
            <h1 class="text-4xl font-semibold tracking-[-0.8px] mb-1 text-[#0d0d0d] dark:text-white">
                {{ __('All Products') }}
            </h1>
            <p class="text-[14px] text-[#666666] dark:text-zinc-400">
                {{ $this->products->total() }} {{ __('items found') }}
            </p>
        </div>

        {{-- Search + filters --}}
        <div class="mb-8 space-y-4">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-[#999999] dark:text-zinc-500 pointer-events-none" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="{{ __('Search products…') }}"
                    class="w-full pl-9 pr-4 py-2.5 text-[14px] rounded-full border border-black/[0.08] dark:border-white/[0.08] bg-white dark:bg-zinc-950 text-[#0d0d0d] dark:text-white placeholder:text-[#999999] dark:placeholder:text-zinc-500 focus:outline-none focus:border-[#18E299] focus:ring-2 focus:ring-[#18E299]/20 transition-all"
                />
            </div>

            <div class="flex flex-wrap gap-2 pb-1">
                <button
                    wire:click="$set('category', '')"
                    class="{{ $category === '' ? 'bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] border-[#18E299]/20' : 'border-black/[0.08] dark:border-white/[0.08] hover:bg-gray-50 dark:hover:bg-zinc-800 text-[#0d0d0d] dark:text-zinc-300' }} px-4 py-1.5 rounded-full text-[13px] font-medium border transition-all cursor-pointer"
                >
                    {{ __('All Items') }}
                </button>

                @foreach ($this->categories as $cat)
                    <button
                        wire:key="pill-{{ $cat->id }}"
                        wire:click="$set('category', '{{ $cat->slug }}')"
                        class="{{ $cat->slug === $category ? 'bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] border-[#18E299]/20' : 'border-black/[0.08] dark:border-white/[0.08] hover:bg-gray-50 dark:hover:bg-zinc-800 text-[#0d0d0d] dark:text-zinc-300' }} px-4 py-1.5 rounded-full text-[13px] font-medium border transition-all cursor-pointer"
                    >
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Product grid --}}
        <div wire:loading.class.delay="opacity-50 pointer-events-none" class="transition-opacity duration-150">
            @if ($this->products->isEmpty())
                <div class="py-24 text-center">
                    <p class="text-[#666666] dark:text-zinc-400 text-[15px]">{{ __('No products found.') }}</p>
                    @if ($search || $category)
                        <button wire:click="$set('search', ''); $set('category', '')" class="mt-4 text-[13px] text-[#0fa76e] dark:text-[#18E299] hover:underline">
                            {{ __('Clear filters') }}
                        </button>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($this->products as $product)
                        <x-product-card
                            wire:key="product-{{ $product->id }}"
                            :product="$product"
                        />
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($this->products->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $this->products->links('partials.shop-pagination') }}
                    </div>
                @endif
            @endif
        </div>
    </section>
</div>
