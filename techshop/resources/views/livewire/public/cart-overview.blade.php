<section class="pt-32 pb-20 max-w-[1200px] mx-auto px-6">
    <div class="mb-12">
        <h2 class="text-4xl font-semibold tracking-[-0.8px] mb-4">Winkelwagen</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            @forelse($items as $item)
                <div class="flex items-center gap-6 p-4 border border-black/[0.08] rounded-xl mb-4 bg-white" wire:key="cart-item-{{ $item->product_id }}">
                    <div class="w-24 h-24 bg-[#fafafa] rounded-lg overflow-hidden flex items-center justify-center border border-black/[0.05]">
                        @if(isset($item->product->hero_image) && $item->product->hero_image)
                            <img src="{{ asset('storage/' . $item->product->hero_image) }}" class="object-cover w-full h-full" alt="{{ $item->product->name }}">
                        @else
                            <span class="text-[10px] font-mono text-gray-300">IMAGE</span>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <h4 class="font-medium text-[16px]">{{ $item->product->name }}</h4>
                        <div class="text-[#666666] text-[14px] mb-4">&euro;{{ number_format($item->unit_price, 2) }} per stuk</div>
                        
                        <div class="flex items-center gap-4">
                            <!-- Quantities -->
                            <div class="flex items-center border border-black/[0.1] rounded-full px-2 py-1">
                                <button wire:click="updateQuantity('{{ $item->id }}', {{ $item->product_id }}, {{ $item->quantity - 1 }})" class="w-8 flex items-center justify-center text-gray-500 hover:text-black transition-colors">-</button>
                                <span class="text-[13px] font-medium w-6 text-center select-none">{{ $item->quantity }}</span>
                                <button wire:click="updateQuantity('{{ $item->id }}', {{ $item->product_id }}, {{ $item->quantity + 1 }})" class="w-8 flex items-center justify-center text-gray-500 hover:text-black transition-colors">+</button>
                            </div>
                            
                            <button wire:click="removeItem('{{ $item->id }}', {{ $item->product_id }})" class="text-red-500 text-[13px] font-medium hover:underline transition-colors">
                                Verwijder
                            </button>
                        </div>
                    </div>

                    <div class="font-semibold text-[15px]">
                        &euro;{{ number_format($item->quantity * $item->unit_price, 2) }}
                    </div>
                </div>
            @empty
                <div class="text-center py-12 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                    <p class="text-gray-500 font-medium text-[15px]">Je winkelwagen is nog leeg!</p>
                </div>
            @endforelse
        </div>

        <!-- Order Summary side bar -->
        <div class="lg:col-span-1">
            <div class="bg-[#fafafa] border border-black/[0.05] p-6 rounded-2xl sticky top-32">
                <h3 class="text-xl font-semibold tracking-tight mb-6">Besteloverzicht</h3>
                
                <div class="flex justify-between mb-4 text-[#666666] text-[14px]">
                    <span>Subtotaal (Excl. BTW)</span>
                    <span>&euro;{{ number_format($total, 2) }}</span>
                </div>
                
                <div class="flex justify-between mb-6 text-[#666666] text-[14px]">
                    <span>BTW (21%)</span>
                    <span>&euro;{{ number_format($vat, 2) }}</span>
                </div>

                <div class="border-t border-black/[0.1] pt-4 mb-8 flex justify-between font-semibold text-[16px]">
                    <span>Totaal</span>
                    <span>&euro;{{ number_format($grandTotal, 2) }}</span>
                </div>

                <button class="w-full bg-[#18E299] text-black hover:bg-[#15c586] transition-colors py-3.5 rounded-full font-medium text-[15px] shadow-sm">
                    Afrekenen
                </button>
            </div>
        </div>
    </div>
</section>
