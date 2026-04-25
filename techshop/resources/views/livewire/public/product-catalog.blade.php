<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Product;
use App\Models\Category;

new class extends Component {
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';
    
    #[Url(history: true)]
    public ?int $categoryId = null;

    public function with(): array
    {
        return [
            'products' => Product::with('category')
                ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
                ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
                ->paginate(12),
            'categories' => Category::all(),
        ];
    }

    public function setCategory(?int $id)
    {
        $this->categoryId = $id;
        $this->resetPage();
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
}; ?>

<x-layouts.app>
    <section class="pt-32 pb-20 max-w-[1200px] mx-auto px-6">
        <div class="mb-12">
            <h2 class="text-4xl font-semibold tracking-[-0.8px] mb-4">All Hardware</h2>
            
            <!-- Realtime Search input -->
            <div class="mb-6">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products by name..." class="w-full max-w-md px-4 py-2 border border-black/[0.08] rounded-[8px] focus:outline-none focus:ring-2 focus:ring-[#18E299]/50 transition-all text-[14px]">
            </div>

            <!-- Category Filters -->
            <div class="flex gap-4 overflow-x-auto pb-4">
                <span wire:click="setCategory(null)" class="{{ $categoryId === null ? 'bg-[#d4fae8] text-[#0fa76e] border border-[#18E299]/20' : 'border border-black/[0.08] hover:bg-gray-50' }} px-4 py-1.5 rounded-full text-[13px] font-medium cursor-pointer transition-colors whitespace-nowrap">
                    All Items
                </span>
                @foreach($categories as $category)
                    <span wire:click="setCategory({{ $category->id }})" class="{{ $categoryId === $category->id ? 'bg-[#d4fae8] text-[#0fa76e] border border-[#18E299]/20' : 'border border-black/[0.08] hover:bg-gray-50' }} px-4 py-1.5 rounded-full text-[13px] font-medium cursor-pointer transition-colors whitespace-nowrap">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="product-grid">
            @forelse($products as $product)
                <a href="/products/{{ $product->slug }}" class="product-card group" wire:key="product-{{ $product->id }}">
                    <div class="aspect-square bg-[#fafafa] rounded-[16px] border border-black/[0.05] mb-4 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#18E299]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-full h-full flex items-center justify-center text-gray-300 font-mono text-[10px]">
                            @if(isset($product->hero_image) && $product->hero_image)
                                <img src="{{ asset('storage/' . $product->hero_image) }}" alt="{{ $product->name }}" class="object-cover w-full h-full">
                            @else
                                IMAGE_PLACEHOLDER
                            @endif
                        </div>
                    </div>
                    <h4 class="text-[16px] font-medium mb-1 group-hover:text-[#18E299] transition-colors">{{ $product->name }}</h4>
                    <p class="text-[14px] text-[#666666] mb-2">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    <div class="text-[15px] font-semibold">&euro;{{ number_format($product->price, 2) }}</div>
                </a>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500 font-medium">
                    No products found matching your criteria.
                </div>
            @endforelse
        </div>

        <div class="mt-12 flex justify-center w-full">
            {{ $products->links() }}
        </div>
    </section>
</x-layouts.app>
