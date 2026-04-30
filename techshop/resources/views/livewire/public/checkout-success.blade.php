<section class="pt-16 pb-20 max-w-[700px] mx-auto px-6 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
    </div>

    <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-3">
        {{ __('Order confirmed') }}
    </div>

    <div class="mb-10">
        {{-- Logical Success Popup/Banner --}}
        <div 
            x-data="{ show: true }" 
            x-show="show"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="bg-[#d4fae8] dark:bg-[#0fa76e]/20 border border-[#18E299]/20 rounded-[24px] p-8 mb-8 relative overflow-hidden group"
        >
            <div class="absolute top-0 right-0 p-4">
                <button @click="show = false" class="text-[#0fa76e] dark:text-[#18E299] hover:opacity-70 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-[#18E299] flex items-center justify-center text-white mb-6 shadow-lg shadow-[#18E299]/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </div>

                <h2 class="text-2xl font-bold text-[#0fa76e] dark:text-[#18E299] mb-2 text-center">
                    {{ __('Order Placed Successfully!') }}
                </h2>
                <p class="text-[15px] text-[#0fa76e]/80 dark:text-[#18E299]/80 mb-2 text-center max-w-md">
                    {{ __('Your order has been confirmed and payment was successful.') }}
                </p>
                <p class="text-[14px] text-[#0fa76e]/60 dark:text-[#18E299]/60 mb-6 text-center max-w-md">
                    {{ __('You will receive a confirmation email shortly.') }}
                </p>

                <a 
                    href="{{ route('my-orders') }}" 
                    wire:navigate
                    class="bg-[#0fa76e] dark:bg-[#18E299] text-white dark:text-[#0d0d0d] px-6 py-2.5 rounded-full text-[14px] font-bold hover:opacity-90 transition-all flex items-center gap-2 shadow-md shadow-[#0fa76e]/20"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    {{ __('View My Orders') }}
                </a>
            </div>
        </div>

        <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-zinc-50 mb-4">
            {{ __('Thank you for your purchase!') }}
        </h1>
    </div>

    {{-- Order reference card --}}
    <div class="bg-[#fafafa] dark:bg-zinc-900 border border-black/[0.05] dark:border-white/[0.05] rounded-[24px] p-6 mb-8 text-left">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase">
                {{ __('Order reference') }}
            </span>
            <span class="font-mono text-[14px] font-semibold text-[#0d0d0d] dark:text-zinc-50">
                #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
            </span>
        </div>

        <div class="border-t border-black/[0.06] dark:border-white/[0.05] pt-4 space-y-3">
            <div class="flex justify-between text-[14px]">
                <span class="text-[#666666] dark:text-zinc-400">{{ __('Name') }}</span>
                <span class="font-medium text-[#0d0d0d] dark:text-zinc-50">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</span>
            </div>
            <div class="flex justify-between text-[14px]">
                <span class="text-[#666666] dark:text-zinc-400">{{ __('Email') }}</span>
                <span class="font-medium text-[#0d0d0d] dark:text-zinc-50">{{ $order->email }}</span>
            </div>
            <div class="flex justify-between text-[14px]">
                <span class="text-[#666666] dark:text-zinc-400">{{ __('Ship to') }}</span>
                <span class="font-medium text-[#0d0d0d] dark:text-zinc-50 text-right">
                    {{ $order->shipping_address_line_1 }}, {{ $order->shipping_city }}
                </span>
            </div>
            <div class="border-t border-black/[0.06] dark:border-white/[0.05] pt-3 flex justify-between text-[15px] font-semibold text-[#0d0d0d] dark:text-zinc-50">
                <span>{{ __('Order total') }}</span>
                <span>&euro;{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a
            href="{{ route('products') }}"
            wire:navigate
            class="inline-block bg-[#0d0d0d] dark:bg-white text-white dark:text-[#0d0d0d] px-8 py-3 rounded-full text-[15px] font-medium shadow-md dark:shadow-none hover:opacity-90 transition-all"
        >
            {{ __('Continue shopping') }}
        </a>

        @auth
            <a
                href="{{ route('my-orders') }}"
                wire:navigate
                class="inline-block bg-white dark:bg-zinc-950 border border-black/[0.08] dark:border-white/[0.08] text-[#0d0d0d] dark:text-zinc-100 px-8 py-3 rounded-full text-[15px] font-medium hover:bg-gray-50 dark:hover:bg-zinc-800 transition-all"
            >
                {{ __('View my orders') }}
            </a>
        @endauth
    </div>
</section>
