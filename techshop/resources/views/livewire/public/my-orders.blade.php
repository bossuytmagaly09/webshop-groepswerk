<div class="max-w-[1200px] mx-auto px-6 py-12">
    <div class="mb-10">
        <div class="text-[11px] font-mono text-[#18E299] tracking-widest uppercase mb-3">
            {{ __('Account — History') }}
        </div>
        <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-white">
            {{ __('My Orders') }}
        </h1>
        <p class="text-[#666666] dark:text-zinc-400 mt-2 text-sm">
            {{ __('View the details and status of your past orders.') }}
        </p>
    </div>

    @if($orders->isEmpty())
        <div class="bg-gray-50 dark:bg-zinc-900/50 border border-gray-100 dark:border-white/[0.05] rounded-[32px] p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-white dark:bg-zinc-800 rounded-2xl flex items-center justify-center border border-black/[0.05] dark:border-white/[0.05] mx-auto mb-6 shadow-sm">
                <svg class="w-8 h-8 text-[#cccccc] dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-zinc-50 mb-2">{{ __('No orders found') }}</h3>
            <p class="text-gray-500 dark:text-zinc-400 mb-8 max-w-md mx-auto">{{ __("You haven't placed any orders yet. Start shopping to see your history here.") }}</p>
            <a href="{{ route('products') }}" class="inline-flex bg-[#0d0d0d] dark:bg-white text-white dark:text-[#0d0d0d] px-8 py-3 rounded-full text-[14px] font-medium hover:opacity-90 transition-all shadow-sm" wire:navigate>
                {{ __('Browse our catalog') }}
            </a>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($orders as $order)
                <div class="group bg-white dark:bg-zinc-900 border border-black/[0.04] dark:border-white/[0.04] rounded-[32px] p-8 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:border-[#18E299]/30 transition-all duration-300">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-black/[0.05] dark:border-white/[0.05] pb-6 mb-6 gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-xl font-bold tracking-tight text-[#0d0d0d] dark:text-white">Order #{{ $order->id }}</span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-mono tracking-widest uppercase bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] border border-[#18E299]/10">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <p class="text-[13px] text-[#666666] dark:text-zinc-400">
                                {{ __('Placed on') }} {{ $order->created_at->format('M d, Y') }} at {{ $order->created_at->format('H:i') }}
                            </p>
                        </div>
                        <div class="md:text-right">
                            <p class="text-[10px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest mb-1 uppercase">{{ __('Total Amount') }}</p>
                            <p class="text-2xl font-bold text-[#0d0d0d] dark:text-white">{{ $order->formatted_total }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50/50 dark:bg-zinc-800/30 border border-transparent group-hover:border-black/[0.03] dark:group-hover:border-white/[0.03] transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white dark:bg-zinc-800 rounded-xl flex items-center justify-center border border-black/[0.05] dark:border-white/[0.05] shadow-sm">
                                        <svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#0d0d0d] dark:text-zinc-50">{{ $item->product_name ?? __('Unnamed Product') }}</p>
                                        <p class="text-[#999999] text-[12px] font-medium">
                                            {{ __('Price per unit:') }} &euro;{{ number_format($item->unit_price, 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-8">
                                    <div class="text-[#666666] dark:text-zinc-400 font-mono text-[14px]">
                                        <span class="text-[11px] uppercase tracking-tighter mr-1 text-zinc-400">{{ __('Qty:') }}</span>{{ $item->quantity }}
                                    </div>
                                    <div class="font-bold text-[#0d0d0d] dark:text-zinc-50 w-24 text-right text-[15px]">
                                        &euro;{{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
