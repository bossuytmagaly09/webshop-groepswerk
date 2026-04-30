<?php

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Layout('layouts.app')] #[Title('Products')] class extends Component {
    use WithPagination, WithFileUploads;

    public bool $isEditing = false;
    public ?int $productId = null;

    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $price = '';
    public string $stock = '0';
    public ?int $category_id = null;
    public $image = null; // Livewire temp upload

    // Huidig opgeslagen afbeelding pad (voor preview bij editeren)
    public ?string $existingImage = null;

    public function updatedName(): void
    {
        if (! $this->isEditing) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function create(): void
    {
        $this->reset(['name', 'slug', 'description', 'price', 'stock', 'category_id', 'image', 'existingImage', 'productId', 'isEditing']);
        $this->resetValidation();
        $this->stock = '0';
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $product = Product::withTrashed()->findOrFail($id);

        $this->productId    = $product->id;
        $this->name         = $product->name;
        $this->slug         = $product->slug;
        $this->description  = $product->description ?? '';
        $this->price        = (string) $product->price;
        $this->stock        = (string) $product->stock;
        $this->category_id  = $product->category_id;
        $this->existingImage = $product->image;
        $this->image        = null;
        $this->isEditing    = true;
    }

    public function save(): void
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:products,slug,' . $this->productId,
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
        ];

        $validated = $this->validate($rules);

        // Verwijder image uit validated array — Actions verwerken dit apart
        unset($validated['image']);

        $uploadedImage = $this->image;

        if ($this->isEditing) {
            $product = Product::withTrashed()->findOrFail($this->productId);
            app(UpdateProductAction::class)->handle($product, $validated, $uploadedImage);
        } else {
            app(CreateProductAction::class)->handle($validated, $uploadedImage);
        }

        $this->dispatch('close-modal', 'product-modal');
        \Flux::toast('Product succesvol ' . ($this->isEditing ? 'bijgewerkt' : 'aangemaakt') . '.');
    }

    public function delete(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->delete();
        \Flux::toast('Product "' . $product->name . '" verwijderd.');
    }

    public function restore(int $id): void
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        \Flux::toast('Product "' . $product->name . '" hersteld.');
    }

    public function with(): array
    {
        return [
            'products'   => Product::query()
                ->withTrashed()
                ->with('category')
                ->orderBy('name')
                ->paginate(10),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ];
    }
}; ?>

<div>
    <div class="max-w-[1200px] mx-auto px-6 pt-12 pb-24">

        {{-- Page header --}}
        <div class="mb-10 flex items-end justify-between gap-6">
            <div>
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-3">
                    {{ __('Admin — CMS') }}
                </div>
                <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-white">
                    {{ __('Products') }}
                </h1>
            </div>

            <flux:modal.trigger name="product-modal">
                <button wire:click="create" class="flex items-center gap-2 bg-[#0d0d0d] dark:bg-white text-white dark:text-[#0d0d0d] hover:opacity-80 text-[14px] font-medium px-6 py-2.5 rounded-full shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    {{ __('New Product') }}
                </button>
            </flux:modal.trigger>
        </div>

        {{-- Table --}}
        <div class="rounded-[16px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[14px] text-left">
                    <thead class="border-b border-black/[0.05] dark:border-white/[0.06]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium w-24"></th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Name') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Category') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Price') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Stock') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Status') }}</th>
                            <th scope="col" class="px-6 py-4 text-right text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.04] dark:divide-white/[0.04]">
                        @foreach ($products as $product)
                            <tr class="group hover:bg-[#fafafa] dark:hover:bg-zinc-800/50">
                                {{-- Thumbnail --}}
                                <td class="pl-6 pr-2 py-4">
                                    @if ($product->image)
                                        <img
                                            src="{{ Storage::disk('public')->url($product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="w-10 h-10 rounded-lg object-cover border border-black/[0.06] dark:border-white/[0.06]"
                                        />
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-[#f0fdf4] dark:bg-zinc-800 border border-black/[0.05] dark:border-white/[0.05] flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-[#cccccc] dark:text-zinc-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                        </div>
                                    @endif
                                </td>
                                {{-- Name --}}
                                <td class="px-6 py-4">
                                    <span class="font-medium text-[#0d0d0d] dark:text-white group-hover:text-[#0fa76e]">
                                        {{ $product->name }}
                                    </span>
                                    @if ($product->slug)
                                        <div class="font-mono text-[11px] text-[#999999] dark:text-zinc-600 mt-0.5">/{{ $product->slug }}</div>
                                    @endif
                                </td>
                                {{-- Category --}}
                                <td class="px-6 py-4 text-[#666666] dark:text-zinc-400 text-[13px]">
                                    {{ $product->category?->name ?? '—' }}
                                </td>
                                {{-- Price --}}
                                <td class="px-6 py-4 text-[#0d0d0d] dark:text-white font-medium">
                                    {{ $product->formatted_price }}
                                </td>
                                {{-- Stock --}}
                                <td class="px-6 py-4">
                                    <span class="font-mono text-[13px] {{ $product->stock > 0 ? 'text-[#0d0d0d] dark:text-white' : 'text-red-500' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if ($product->trashed())
                                        <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-medium bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20 min-w-[70px]">
                                            {{ __('Deleted') }}
                                        </span>
                                    @else
                                        <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-medium bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] border border-[#18E299]/20 min-w-[70px]">
                                            {{ __('Active') }}
                                        </span>
                                    @endif
                                </td>
                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center gap-3 justify-end text-[#cccccc] dark:text-zinc-600 group-hover:text-[#999999] dark:group-hover:text-zinc-400">
                                        @if ($product->trashed())
                                            <button wire:click="restore({{ $product->id }})" class="hover:text-[#0fa76e] transition-colors" title="{{ __('Restore') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/></svg>
                                            </button>
                                        @else
                                            <flux:modal.trigger name="product-modal">
                                                <button wire:click="edit({{ $product->id }})" class="hover:text-[#0d0d0d] dark:hover:text-white transition-colors" title="{{ __('Edit') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                </button>
                                            </flux:modal.trigger>
                                            <button wire:click="delete({{ $product->id }})" class="hover:text-red-500 transition-colors" title="{{ __('Delete') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($products->isEmpty())
                <div class="py-16 text-center">
                    <p class="text-[#999999] text-[14px]">{{ __('No products yet.') }}</p>
                </div>
            @endif

            @if ($products->hasPages())
                <div class="px-6 py-4 border-t border-black/[0.05] dark:border-white/[0.06]">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal --}}
    <flux:modal name="product-modal" class="md:w-[620px]">
        <form wire:submit.prevent="save">
            <div class="mb-6">
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-1">
                    {{ $isEditing ? __('Edit') : __('New') }}
                </div>
                <h2 class="text-2xl font-semibold tracking-[-0.5px] text-[#0d0d0d] dark:text-white">
                    {{ $isEditing ? __('Edit Product') : __('Create Product') }}
                </h2>
            </div>

            <div class="space-y-5 mb-8">
                {{-- Name --}}
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Name') }}</flux:label>
                    <flux:input wire:model.live.debounce="name" placeholder="{{ __('e.g. RTX 4090') }}" />
                    <flux:error name="name" />
                </flux:field>

                {{-- Slug --}}
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Slug') }}</flux:label>
                    <flux:input wire:model="slug" placeholder="{{ __('e.g. rtx-4090') }}" />
                    <flux:error name="slug" />
                </flux:field>

                {{-- Category --}}
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Category') }}</flux:label>
                    <flux:select wire:model="category_id" placeholder="{{ __('Select a category...') }}">
                        @foreach ($categories as $cat)
                            <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="category_id" />
                </flux:field>

                {{-- Price & Stock side by side --}}
                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label class="text-[13px] font-medium">{{ __('Price (€)') }}</flux:label>
                        <flux:input wire:model="price" type="number" step="0.01" min="0" placeholder="0.00" />
                        <flux:error name="price" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="text-[13px] font-medium">{{ __('Stock') }}</flux:label>
                        <flux:input wire:model="stock" type="number" min="0" placeholder="0" />
                        <flux:error name="stock" />
                    </flux:field>
                </div>

                {{-- Description --}}
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Description') }}</flux:label>
                    <flux:textarea wire:model="description" placeholder="{{ __('Optional product description...') }}" rows="3" />
                    <flux:error name="description" />
                </flux:field>

                {{-- Image upload --}}
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Image') }}</flux:label>

                    {{-- Preview bestaande afbeelding --}}
                    @if ($existingImage && ! $image)
                        <div class="mb-2 flex items-center gap-3">
                            <img src="{{ Storage::disk('public')->url($existingImage) }}" class="w-16 h-16 rounded-lg object-cover border border-black/[0.06] dark:border-white/[0.06]" alt="Current image" />
                            <span class="text-[12px] text-[#999999]">{{ __('Current image') }}</span>
                        </div>
                    @endif

                    {{-- Preview nieuwe upload --}}
                    @if ($image)
                        <div class="mb-2">
                            <img src="{{ $image->temporaryUrl() }}" class="w-16 h-16 rounded-lg object-cover border border-[#18E299]/30" alt="Preview" />
                        </div>
                    @endif

                    <input
                        wire:model="image"
                        type="file"
                        accept="image/*"
                        class="text-[13px] text-[#666666] dark:text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[12px] file:font-medium file:bg-[#d4fae8] file:text-[#0fa76e] hover:file:bg-[#18E299]/20 cursor-pointer"
                    />
                    <flux:error name="image" />

                    <div wire:loading wire:target="image" class="mt-1 text-[12px] text-[#999999]">
                        {{ __('Uploading...') }}
                    </div>
                </flux:field>
            </div>

            <div class="flex items-center gap-3 justify-end">
                <flux:modal.close>
                    <button type="button" class="text-[14px] font-medium text-[#666666] hover:text-[#0d0d0d] dark:hover:text-white px-4 py-2">
                        {{ __('Cancel') }}
                    </button>
                </flux:modal.close>
                <button type="submit" class="bg-[#0d0d0d] dark:bg-[#18E299] hover:opacity-80 text-white dark:text-[#0d0d0d] text-[14px] font-medium px-6 py-2.5 rounded-full shadow-sm">
                    {{ __('Save changes') }}
                </button>
            </div>
        </form>
    </flux:modal>
</div>
