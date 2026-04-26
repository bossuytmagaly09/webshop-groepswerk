<?php

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] #[Title('Categories')] class extends Component {
    use WithPagination;

    public $isEditing = false;
    public $categoryId = null;

    public string $name = '';
    public string $slug = '';
    public string $description = '';

    public function updatedName()
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function create()
    {
        $this->reset(['name', 'slug', 'description', 'categoryId', 'isEditing']);
        $this->resetValidation();
    }

    public function edit($id)
    {
        $this->resetValidation();
        $category = Category::withTrashed()->findOrFail($id);
        
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->isEditing = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $this->categoryId,
            'description' => 'nullable|string',
        ];
        
        $validatedData = $this->validate($rules);

        Category::withTrashed()->updateOrCreate(
            ['id' => $this->categoryId],
            $validatedData
        );

        $this->dispatch('close-modal', 'category-modal');
        \Flux::toast('Categorie succesvol ' . ($this->isEditing ? 'bijgewerkt' : 'aangemaakt') . '.');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        \Flux::toast('Categorie ' . $category->name . ' succesvol verwijderd.');
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
        \Flux::toast('Categorie ' . $category->name . ' succesvol hersteld.');
    }

    public function with(): array
    {
        return [
            'categories' => Category::query()
                ->withTrashed()
                ->orderBy('name')
                ->paginate(10)
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
                    {{ __('Categories') }}
                </h1>
            </div>

            <flux:modal.trigger name="category-modal">
                <button wire:click="create" class="flex items-center gap-2 bg-[#0d0d0d] dark:bg-white text-white dark:text-[#0d0d0d] hover:opacity-80 transition-all text-[14px] font-medium px-6 py-2.5 rounded-full shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    {{ __('New Category') }}
                </button>
            </flux:modal.trigger>
        </div>

        {{-- Table list --}}
        <div class="rounded-[16px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[14px] text-left">
                    <thead class="border-b border-black/[0.05] dark:border-white/[0.06]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Name') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Slug') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Status') }}</th>
                            <th scope="col" class="px-6 py-4 text-right text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.04] dark:divide-white/[0.04]">
                        @foreach ($categories as $category)
                            <tr class="group hover:bg-[#fafafa] dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-5">
                                    <span class="font-medium text-[#0d0d0d] dark:text-white group-hover:text-[#0fa76e] transition-colors">
                                        {{ $category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="font-mono text-[13px] text-[#999999] dark:text-zinc-500">/{{ $category->slug }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    @if ($category->trashed())
                                        <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-medium bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20 min-w-[70px]">
                                            {{ __('Deleted') }}
                                        </span>
                                    @else
                                        <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-medium bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] border border-[#18E299]/20 min-w-[70px]">
                                            {{ __('Active') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center gap-3 justify-end text-[#cccccc] dark:text-zinc-600 group-hover:text-[#999999] dark:group-hover:text-zinc-400 transition-colors">
                                        @if ($category->trashed())
                                            <button wire:click="restore({{ $category->id }})" class="hover:text-[#0fa76e] transition-colors" title="{{ __('Restore') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/></svg>
                                            </button>
                                        @else
                                            <flux:modal.trigger name="category-modal">
                                                <button wire:click="edit({{ $category->id }})" class="hover:text-[#0d0d0d] dark:hover:text-white transition-colors" title="{{ __('Edit') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                </button>
                                            </flux:modal.trigger>
                                            <button wire:click="delete({{ $category->id }})" class="hover:text-red-500 transition-colors" title="{{ __('Delete') }}">
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

            @if ($categories->isEmpty())
                <div class="py-16 text-center">
                    <p class="text-[#999999] text-[14px]">{{ __('No categories yet.') }}</p>
                </div>
            @endif

            @if ($categories->hasPages())
                <div class="px-6 py-4 border-t border-black/[0.05] dark:border-white/[0.06]">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal --}}
    <flux:modal name="category-modal" class="md:w-[500px]">
        <form wire:submit.prevent="save">
            <div class="mb-6">
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-1">
                    {{ $isEditing ? __('Edit') : __('New') }}
                </div>
                <h2 class="text-2xl font-semibold tracking-[-0.5px] text-[#0d0d0d] dark:text-white">
                    {{ $isEditing ? __('Edit Category') : __('Create Category') }}
                </h2>
            </div>

            <div class="space-y-5 mb-8">
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Name') }}</flux:label>
                    <flux:input wire:model.live.debounce="name" placeholder="{{ __('e.g. Graphics Cards') }}" />
                    <flux:error name="name" />
                </flux:field>
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Slug') }}</flux:label>
                    <flux:input wire:model="slug" placeholder="{{ __('e.g. graphics-cards') }}" />
                    <flux:error name="slug" />
                </flux:field>
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Description') }}</flux:label>
                    <flux:textarea wire:model="description" placeholder="{{ __('Optional short description...') }}" rows="3" />
                    <flux:error name="description" />
                </flux:field>
            </div>

            <div class="flex items-center gap-3 justify-end">
                <flux:modal.close>
                    <button type="button" class="text-[14px] font-medium text-[#666666] hover:text-[#0d0d0d] dark:hover:text-white transition-colors px-4 py-2">
                        {{ __('Cancel') }}
                    </button>
                </flux:modal.close>
                <button type="submit" class="bg-[#0d0d0d] dark:bg-[#18E299] hover:opacity-80 text-white dark:text-[#0d0d0d] text-[14px] font-medium px-6 py-2.5 rounded-full transition-all">
                    {{ __('Save changes') }}
                </button>
            </div>
        </form>
    </flux:modal>
</div>
