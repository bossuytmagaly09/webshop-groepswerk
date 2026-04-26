<div class="max-w-[1200px] mx-auto px-6 py-12">
    <div class="mb-8 font-semibold">
        <h1 class="text-3xl tracking-tight mb-2">Mijn Bestellingen</h1>
        <p class="text-[#666666] font-normal text-sm">Bekijk de details en statussen van je eerdere bestellingen.</p>
    </div>

    @if($orders->isEmpty())
        <div class="bg-gray-50 border border-gray-100 rounded-[24px] p-12 text-center shadow-sm">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Geen bestellingen gevonden</h3>
            <p class="text-gray-500 mb-6">Je hebt momenteel nog geen bestelhistorie bij ons.</p>
            <a href="{{ route('products') }}" class="inline-flex bg-[#0d0d0d] text-white px-6 py-2 rounded-full text-[14px] font-medium hover:opacity-90 transition-all shadow-sm">Bekijk onze catalogus</a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-[0_2px_4px_rgba(0,0,0,0.03)] hover:border-black/[0.1] transition-all">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-black/[0.05] pb-4 mb-4 gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-lg font-semibold tracking-tight">Order #{{ $order->id }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-mono tracking-widest uppercase bg-[#d4fae8] text-[#0fa76e]">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <p class="text-[14px] text-[#666666]">Geplaatst op {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[12px] font-mono text-[#0fa76e] tracking-widest mb-1 uppercase">Totaalbedrag</p>
                            <p class="text-xl font-semibold">&euro;{{ number_format($order->total_price, 2, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between text-[14px]">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center border border-black/[0.05]">
                                        <svg class="w-5 h-5 text-[#0d0d0d]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-[#0d0d0d]">{{ $item->product_name ?? 'Inboedel' }}</p>
                                        <p class="text-[#666666] text-xs">Prijs per stuk: &euro;{{ number_format($item->unit_price, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="font-medium text-[#666666]">
                                    {{ $item->quantity }}x
                                </div>
                                <div class="font-medium text-[#0d0d0d] w-20 text-right">
                                    &euro;{{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
