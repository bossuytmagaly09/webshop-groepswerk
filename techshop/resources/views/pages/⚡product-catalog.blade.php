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
            <h1 class="text-4xl font-semibold tracking-[-0.8px] mb-1">
                {{ __('All Products') }}
            </h1>
            <p class="text-[14px] text-[#666666]">
                {{ $this->products->total() }} {{ __('items found') }}
            </p>
        </div>

        {{-- Search + filters --}}
        <div class="mb-8 space-y-4">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-[#999999] pointer-events-none" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="{{ __('Search products…') }}"
                    class="w-full pl-9 pr-4 py-2.5 text-[14px] rounded-full border border-black/[0.08] bg-white placeholder:text-[#999999] focus:outline-none focus:border-[#18E299] focus:ring-2 focus:ring-[#18E299]/20 transition-all"
                />
            </div>

            <div class="flex flex-wrap gap-2 pb-1">
                <button
                    wire:click="$set('category', '')"
                    class="{{ $category === '' ? 'bg-[#d4fae8] text-[#0fa76e] border-[#18E299]/20' : 'border-black/[0.08] hover:bg-gray-50' }} px-4 py-1.5 rounded-full text-[13px] font-medium border transition-all cursor-pointer"
                >
                    {{ __('All Items') }}
                </button>

                @foreach ($this->categories as $cat)
                    <button
                        wire:key="pill-{{ $cat->id }}"
                        wire:click="$set('category', '{{ $cat->id }}')"
                        class="{{ (string) $cat->id === $category ? 'bg-[#d4fae8] text-[#0fa76e] border-[#18E299]/20' : 'border-black/[0.08] hover:bg-gray-50' }} px-4 py-1.5 rounded-full text-[13px] font-medium border transition-all cursor-pointer"
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
                    <p class="text-[#666666] text-[15px]">{{ __('No products found.') }}</p>
                    @if ($search || $category)
                        <button wire:click="$set('search', ''); $set('category', '')" class="mt-4 text-[13px] text-[#0fa76e] hover:underline">
                            {{ __('Clear filters') }}
                        </button>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($this->products as $product)
                        <a
                            wire:key="product-{{ $product->id }}"
                            href="/products/{{ $product->slug }}"
                            class="group block"
                        >
                            <div class="aspect-square bg-[#fafafa] rounded-[16px] border border-black/[0.05] mb-4 overflow-hidden relative">
                                <div class="absolute inset-0 bg-gradient-to-tr from-[#18E299]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="w-full h-full flex items-center justify-center font-mono text-[10px] text-gray-300 uppercase tracking-widest">
                                    {{ __('No image') }}
                                </div>

                                @if ($product->stock === 0)
                                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-[10px] font-medium text-[#999999] uppercase tracking-widest px-2.5 py-1 rounded-full border border-black/[0.05]">
                                        {{ __('Out of stock') }}
                                    </div>
                                @elseif ($product->stock <= 5)
                                    <div class="absolute top-3 left-3 bg-[#fff8e1]/90 backdrop-blur-sm text-[10px] font-medium text-[#b45309] uppercase tracking-widest px-2.5 py-1 rounded-full border border-[#f59e0b]/20">
                                        {{ __('Low stock') }}
                                    </div>
                                @endif
                            </div>

                            <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-1">
                                {{ $product->category?->name ?? '—' }}
                            </div>
                            <h3 class="text-[16px] font-medium mb-1 group-hover:text-[#18E299] transition-colors leading-snug">
                                {{ $product->name }}
                            </h3>
                            <p class="text-[13px] text-[#666666] mb-2 line-clamp-2">
                                {{ $product->description }}
                            </p>
                            <div class="text-[15px] font-semibold">
                                € {{ number_format((float) $product->price, 2, ',', '.') }}
                            </div>
                        </a>
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
