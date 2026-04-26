<section class="pt-16 pb-20 max-w-[1200px] mx-auto px-6">
    <div class="mb-12">
        <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-3">
            {{ __('Your cart') }}
        </div>
        <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px]">
            {{ __('Review your order') }}
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Cart items --}}
        <div class="lg:col-span-2">
            @forelse($items as $item)
                <div
                    wire:key="cart-item-{{ $item->product_id }}"
                    class="flex items-center gap-6 p-4 border border-black/[0.06] rounded-[16px] mb-4 bg-white shadow-sm"
                >
                    <div class="w-24 h-24 bg-gradient-to-br from-[#fafafa] to-[#f0fdf4] rounded-[12px] overflow-hidden flex items-center justify-center border border-black/[0.05]">
                        @if(isset($item->product->hero_image) && $item->product->hero_image)
                            <img src="{{ asset('storage/' . $item->product->hero_image) }}" class="object-cover w-full h-full" alt="{{ $item->product->name }}">
                        @else
                            <span class="font-mono text-[10px] text-gray-300 tracking-widest uppercase">{{ __('No image') }}</span>
                        @endif
                    </div>

                    <div class="flex-1">
                        <h4 class="font-medium text-[16px]">{{ $item->product->name }}</h4>
                        <div class="text-[#666666] text-[14px] mb-4">&euro;{{ number_format($item->unit_price, 2) }} {{ __('per item') }}</div>

                        <div class="flex items-center gap-4">
                            <div class="flex items-center border border-black/[0.08] rounded-full px-2 py-1">
                                <button
                                    type="button"
                                    wire:click="updateQuantity('{{ $item->id }}', {{ $item->product_id }}, {{ $item->quantity - 1 }})"
                                    class="w-8 flex items-center justify-center text-[#666666] hover:text-[#0d0d0d] transition-colors"
                                    aria-label="{{ __('Decrease quantity') }}"
                                >
                                    &minus;
                                </button>
                                <span class="text-[13px] font-medium w-6 text-center select-none">{{ $item->quantity }}</span>
                                <button
                                    type="button"
                                    wire:click="updateQuantity('{{ $item->id }}', {{ $item->product_id }}, {{ $item->quantity + 1 }})"
                                    class="w-8 flex items-center justify-center text-[#666666] hover:text-[#0d0d0d] transition-colors"
                                    aria-label="{{ __('Increase quantity') }}"
                                >
                                    +
                                </button>
                            </div>

                            <button
                                type="button"
                                wire:click="removeItem('{{ $item->id }}', {{ $item->product_id }})"
                                class="inline-flex items-center gap-1.5 text-red-500 text-[13px] font-medium hover:underline transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                {{ __('Remove') }}
                            </button>
                        </div>
                    </div>

                    <div class="font-semibold text-[15px]">
                        &euro;{{ number_format($item->quantity * $item->unit_price, 2) }}
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6 border border-dashed border-black/[0.1] rounded-[24px] bg-[#fafafa]/60">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#d4fae8] text-[#0fa76e] mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="8" cy="21" r="1"/>
                            <circle cx="19" cy="21" r="1"/>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>
                    </span>
                    <p class="text-[#0d0d0d] font-medium text-[16px] mb-1">{{ __('Your cart is empty') }}</p>
                    <p class="text-[#666666] text-[14px] mb-6">{{ __('Looks like you haven’t added anything yet.') }}</p>
                    <a
                        href="{{ route('products') }}"
                        class="inline-block bg-[#0d0d0d] text-white px-8 py-3 rounded-full text-[15px] font-medium shadow-md hover:opacity-90 transition-all"
                        wire:navigate
                    >
                        {{ __('Browse Collection') }}
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-1">
            <div class="bg-[#fafafa] border border-black/[0.05] p-6 rounded-[24px] sticky top-32">
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-3">
                    {{ __('Summary') }}
                </div>
                <h2 class="text-xl font-semibold tracking-[-0.4px] mb-6">
                    {{ __('Order summary') }}
                </h2>

                <div class="flex justify-between mb-4 text-[#666666] text-[14px]">
                    <span>{{ __('Subtotal (excl. VAT)') }}</span>
                    <span>&euro;{{ number_format($total, 2) }}</span>
                </div>

                <div class="flex justify-between mb-6 text-[#666666] text-[14px]">
                    <span>{{ __('VAT (21%)') }}</span>
                    <span>&euro;{{ number_format($vat, 2) }}</span>
                </div>

                <div class="border-t border-black/[0.08] pt-4 mb-8 flex justify-between font-semibold text-[16px]">
                    <span>{{ __('Total') }}</span>
                    <span>&euro;{{ number_format($grandTotal, 2) }}</span>
                </div>

                <button
                    type="button"
                    @class([
                        'w-full px-8 py-3 rounded-full text-[15px] font-medium shadow-md transition-all',
                        'bg-[#0d0d0d] text-white hover:opacity-90' => $items->isNotEmpty(),
                        'bg-[#0d0d0d]/40 text-white cursor-not-allowed' => $items->isEmpty(),
                    ])
                    @disabled($items->isEmpty())
                >
                    {{ __('Proceed to Checkout') }}
                </button>

                <a
                    href="{{ route('products') }}"
                    class="mt-3 block text-center bg-white border border-black/[0.08] text-[#0d0d0d] px-8 py-3 rounded-full text-[15px] font-medium hover:bg-gray-50 transition-all"
                    wire:navigate
                >
                    {{ __('Continue shopping') }}
                </a>
            </div>
        </div>
    </div>
</section>
