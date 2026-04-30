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

    <div class="rounded-[16px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-[14px] text-left">
                <thead class="border-b border-black/[0.05] dark:border-white/[0.06] bg-gray-50/50 dark:bg-white/[0.02]">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#18E299] tracking-widest uppercase font-medium w-24">#</th>
                        <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#18E299] tracking-widest uppercase font-medium">{{ __('Items') }}</th>
                        <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#18E299] tracking-widest uppercase font-medium">{{ __('Date') }}</th>
                        <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#18E299] tracking-widest uppercase font-medium">{{ __('Status') }}</th>
                        <th scope="col" class="px-6 py-4 text-right text-[11px] font-mono text-[#18E299] tracking-widest uppercase font-medium">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[0.04] dark:divide-white/[0.04]">
                    @forelse ($orders as $order)
                        <tr class="group hover:bg-[#fafafa] dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-5">
                                <span class="font-bold text-[#0d0d0d] dark:text-white group-hover:text-[#18E299] transition-colors">
                                    #{{ $order->id }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-3">
                                    @foreach($order->orderItems as $item)
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-[#f0fdf4] dark:bg-zinc-800 border border-black/[0.05] dark:border-white/[0.05] overflow-hidden flex-shrink-0">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ Storage::url($item->product->image) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-[#cccccc] dark:text-zinc-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-sm">
                                                <div class="font-medium text-[#0d0d0d] dark:text-zinc-200">
                                                    <span class="font-bold text-[#18E299]/70 mr-1">{{ $item->quantity }}x</span>
                                                    {{ $item->product_name ?? __('Unnamed Product') }}
                                                </div>
                                                <div class="text-[12px] text-[#999999] dark:text-zinc-500">
                                                    &euro;{{ number_format($item->unit_price, 2, ',', '.') }} {{ __('per unit') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="font-mono text-[13px] text-[#999999] dark:text-zinc-500">
                                    {{ $order->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-medium border min-w-[80px] {{ match($order->status) {
                                    \App\Enums\OrderStatus::PAID => 'bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] border-[#18E299]/20',
                                    \App\Enums\OrderStatus::PENDING => 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
                                    \App\Enums\OrderStatus::CANCELLED => 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-200 dark:border-red-500/20',
                                    default => 'bg-zinc-100 dark:bg-zinc-500/10 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-500/20',
                                } }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-lg font-bold text-[#0d0d0d] dark:text-white">
                                    {{ $order->formatted_total }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <p class="text-[#999999] text-[14px]">{{ __('No orders yet.') }}</p>
                                <div class="mt-6">
                                    <a href="{{ route('products') }}" wire:navigate class="inline-flex items-center gap-2 bg-[#0d0d0d] dark:bg-white text-white dark:text-[#0d0d0d] hover:opacity-80 transition-all text-[14px] font-medium px-6 py-2.5 rounded-full shadow-sm">
                                        {{ __('Start Shopping') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
