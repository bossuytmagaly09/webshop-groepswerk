<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] #[Title('Orders')] class extends Component {
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'orders' => Order::query()
                ->with(['user', 'orderItems'])
                ->when($this->search, fn ($q) => $q->search($this->search))
                ->when($this->statusFilter, fn ($q) => $q->withStatus(OrderStatus::from($this->statusFilter)))
                ->latest()
                ->paginate(15),
            'statuses' => OrderStatus::cases(),
        ];
    }
}; ?>

<div>
    <div class="max-w-[1200px] mx-auto px-6 pt-12 pb-24">

        {{-- Page header --}}
        <div class="mb-10">
            <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-3">
                {{ __('Admin — CMS') }}
            </div>
            <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-white">
                {{ __('Orders') }}
            </h1>
        </div>

        {{-- Filters toolbar --}}
        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-[#cccccc] dark:text-zinc-600 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="{{ __('Search by order #, name or email…') }}"
                    class="w-full pl-10 pr-4 py-2.5 text-[14px] rounded-[12px] border border-black/[0.08] dark:border-white/[0.08] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-white placeholder-[#cccccc] dark:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/30 focus:border-[#0fa76e]/50"
                />
            </div>
            <select
                wire:model.live="statusFilter"
                class="sm:w-44 px-4 py-2.5 text-[14px] rounded-[12px] border border-black/[0.08] dark:border-white/[0.08] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/30 focus:border-[#0fa76e]/50"
            >
                <option value="">{{ __('All statuses') }}</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        {{-- Table --}}
        <div class="rounded-[16px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[14px] text-left">
                    <thead class="border-b border-black/[0.05] dark:border-white/[0.06]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Order #') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Customer') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Date') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Items') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Total') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Status') }}</th>
                            <th scope="col" class="px-6 py-4 text-right text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.04] dark:divide-white/[0.04]">
                        @foreach ($orders as $order)
                            <tr class="group hover:bg-[#fafafa] dark:hover:bg-zinc-800/50 transition-colors">
                                {{-- Order # --}}
                                <td class="px-6 py-5">
                                    <a href="{{ route('dashboard.orders.show', $order) }}" wire:navigate class="font-mono font-medium text-[#0d0d0d] dark:text-white group-hover:text-[#0fa76e] transition-colors">
                                        #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                {{-- Customer --}}
                                <td class="px-6 py-5">
                                    @if ($order->user)
                                        <span class="font-medium text-[#0d0d0d] dark:text-white">{{ $order->user->name }}</span>
                                        <div class="text-[12px] text-[#999999] dark:text-zinc-500 mt-0.5">{{ $order->user->email }}</div>
                                    @elseif ($order->shipping_first_name)
                                        <span class="font-medium text-[#0d0d0d] dark:text-white">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</span>
                                        @if ($order->email)
                                            <div class="text-[12px] text-[#999999] dark:text-zinc-500 mt-0.5">{{ $order->email }}</div>
                                        @endif
                                    @else
                                        <span class="text-[#999999] dark:text-zinc-500 italic">{{ __('Guest') }}</span>
                                    @endif
                                </td>
                                {{-- Date --}}
                                <td class="px-6 py-5 text-[#666666] dark:text-zinc-400 text-[13px]">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                {{-- Items --}}
                                <td class="px-6 py-5 text-[#666666] dark:text-zinc-400 font-mono text-[13px]">
                                    {{ $order->orderItems->count() }} {{ Str::plural('item', $order->orderItems->count()) }}
                                </td>
                                {{-- Total --}}
                                <td class="px-6 py-5 font-medium text-[#0d0d0d] dark:text-white">
                                    {{ $order->formatted_total }}
                                </td>
                                {{-- Status --}}
                                <td class="px-6 py-5">
                                    <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-semibold min-w-[80px] {{ $order->status->color() }}">
                                        {{ $order->status->label() }}
                                    </span>
                                </td>
                                {{-- Actions --}}
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center gap-3 justify-end text-[#cccccc] dark:text-zinc-600 group-hover:text-[#999999] dark:group-hover:text-zinc-400 transition-colors">
                                        <a href="{{ route('dashboard.orders.show', $order) }}" wire:navigate class="hover:text-[#0d0d0d] dark:hover:text-white transition-colors" title="{{ __('View') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($orders->isEmpty())
                <div class="py-16 text-center">
                    <p class="text-[#999999] text-[14px]">{{ __('No orders found.') }}</p>
                </div>
            @endif

            @if ($orders->hasPages())
                <div class="px-6 py-4 border-t border-black/[0.05] dark:border-white/[0.06]">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
