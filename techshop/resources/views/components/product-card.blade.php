@props(['product'])

<a
    href="/products/{{ $product->slug }}"
    class="group block"
>
    <div class="aspect-square bg-[#fafafa] dark:bg-zinc-900 rounded-[16px] border border-black/[0.05] dark:border-white/[0.05] mb-4 overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-tr from-[#18E299]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        @if ($product->image)
            <img src="{{ Storage::disk('public')->url($product->image) }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <div class="w-full h-full flex items-center justify-center font-mono text-[10px] text-gray-300 dark:text-zinc-700 uppercase tracking-widest">
                {{ __('No image') }}
            </div>
        @endif

        @if ($product->stock === 0)
            <div class="absolute top-3 left-3 bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm text-[10px] font-medium text-[#999999] dark:text-zinc-400 uppercase tracking-widest px-2.5 py-1 rounded-full border border-black/[0.05] dark:border-white/[0.05]">
                {{ __('Out of stock') }}
            </div>
        @elseif ($product->stock <= 5)
            <div class="absolute top-3 left-3 bg-[#fff8e1]/90 dark:bg-[#fff8e1]/10 backdrop-blur-sm text-[10px] font-medium text-[#b45309] dark:text-[#fcd34d] uppercase tracking-widest px-2.5 py-1 rounded-full border border-[#f59e0b]/20 dark:border-[#fcd34d]/20">
                {{ __('Low stock') }}
            </div>
        @endif
    </div>

    <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-1">
        {{ $product->category?->name ?? '—' }}
    </div>
    <h3 class="text-[16px] font-medium mb-1 group-hover:text-[#18E299] text-[#0d0d0d] dark:text-white transition-colors leading-snug">
        {{ $product->name }}
    </h3>
    @if (filled($product->description))
        <p class="text-[13px] text-[#666666] dark:text-zinc-400 mb-2 line-clamp-2">
            {{ $product->description }}
        </p>
    @endif
    <div class="text-[15px] font-semibold text-[#0d0d0d] dark:text-white">
        {{ $product->formatted_price }}
    </div>
</a>
