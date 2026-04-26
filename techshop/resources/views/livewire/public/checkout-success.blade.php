<section class="pt-16 pb-20 max-w-[700px] mx-auto px-6 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
    </div>

    <div class="text-[11px] font-mono text-[#0fa76e] dark:text-[#18E299] tracking-widest uppercase mb-3">
        {{ __('Order confirmed') }}
    </div>

    <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-zinc-50 mb-4">
        {{ __('Thank you!') }}
    </h1>

    <p class="text-[#666666] dark:text-zinc-400 text-[16px] mb-2">
        {{ __('Your order has been placed successfully.') }}
    </p>
    <p class="text-[#666666] dark:text-zinc-400 text-[15px] mb-8">
        {{ __('Payment will be processed once our team confirms your order.') }}
    </p>

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
