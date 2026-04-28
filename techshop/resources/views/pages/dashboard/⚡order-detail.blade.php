<?php

use App\Actions\Orders\UpdateOrderStatusAction;
use App\Enums\OrderStatus;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('Order Detail')] class extends Component {
    #[Locked]
    public int $orderId;

    public string $newStatus = '';

    public function mount(Order $order): void
    {
        $this->orderId = $order->id;
    }

    #[Computed]
    public function order(): Order
    {
        return Order::with(['user', 'orderItems'])->findOrFail($this->orderId);
    }

    #[Computed]
    public function availableTransitions(): array
    {
        return $this->order->status->allowedTransitions();
    }

    public function updateStatus(UpdateOrderStatusAction $action): void
    {
        $this->validate(['newStatus' => 'required|string']);

        $newStatus = OrderStatus::from($this->newStatus);
        $action->handle($this->order, $newStatus);

        unset($this->order);
        unset($this->availableTransitions);

        $this->newStatus = '';
        \Flux::toast('Status bijgewerkt naar ' . $newStatus->label() . '.');
    }
}; ?>

<div>
    <div class="max-w-[1200px] mx-auto px-6 pt-12 pb-24">

        {{-- Back link --}}
        <div class="mb-8">
            <a href="{{ route('dashboard.orders') }}" wire:navigate class="inline-flex items-center gap-2 text-[13px] text-[#999999] dark:text-zinc-500 hover:text-[#0fa76e] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                {{ __('Back to orders') }}
            </a>
        </div>

        {{-- Page header --}}
        <div class="mb-10 flex flex-wrap items-start gap-4 justify-between">
            <div>
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-3">
                    {{ __('Admin — CMS') }}
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-white font-mono">
                        #{{ str_pad($this->order->id, 6, '0', STR_PAD_LEFT) }}
                    </h1>
                    <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-semibold {{ $this->order->status->color() }}">
                        {{ $this->order->status->label() }}
                    </span>
                </div>
                <div class="mt-2 text-[13px] text-[#999999] dark:text-zinc-500">
                    {{ __('Placed') }} {{ $this->order->created_at->format('d M Y, H:i') }}
                    @if ($this->order->updated_at->ne($this->order->created_at))
                        · {{ __('Updated') }} {{ $this->order->updated_at->format('d M Y, H:i') }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Two-column grid: Customer info + Status change --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            {{-- Customer info card --}}
            <div class="rounded-[16px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 p-6">
                <h2 class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-4">{{ __('Customer') }}</h2>

                @if ($this->order->user)
                    <div class="space-y-1 mb-4">
                        <div class="font-medium text-[#0d0d0d] dark:text-white">{{ $this->order->user->name }}</div>
                        <div class="text-[13px] text-[#666666] dark:text-zinc-400">{{ $this->order->user->email }}</div>
                    </div>
                @else
                    <div class="space-y-1 mb-4">
                        <div class="font-medium text-[#0d0d0d] dark:text-white">
                            {{ trim($this->order->shipping_first_name . ' ' . $this->order->shipping_last_name) ?: __('Guest') }}
                        </div>
                        @if ($this->order->email)
                            <div class="text-[13px] text-[#666666] dark:text-zinc-400">{{ $this->order->email }}</div>
                        @endif
                        <div class="text-[11px] font-mono text-amber-600 dark:text-amber-400 mt-1">{{ __('Guest order') }}</div>
                    </div>
                @endif

                @if ($this->order->shipping_address_line_1)
                    <div class="pt-4 border-t border-black/[0.05] dark:border-white/[0.06]">
                        <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-2">{{ __('Shipping address') }}</div>
                        <address class="not-italic text-[13px] text-[#666666] dark:text-zinc-400 space-y-0.5 leading-relaxed">
                            <div>{{ $this->order->shipping_address_line_1 }}</div>
                            @if ($this->order->shipping_address_line_2)
                                <div>{{ $this->order->shipping_address_line_2 }}</div>
                            @endif
                            <div>{{ $this->order->shipping_postcode }} {{ $this->order->shipping_city }}</div>
                            @if ($this->order->shipping_country)
                                <div>{{ $this->order->shipping_country }}</div>
                            @endif
                            @if ($this->order->shipping_phone)
                                <div class="pt-1">{{ $this->order->shipping_phone }}</div>
                            @endif
                        </address>
                    </div>
                @endif
            </div>

            {{-- Status change card --}}
            <div class="rounded-[16px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 p-6">
                <h2 class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-4">{{ __('Change status') }}</h2>

                <div class="mb-4">
                    <div class="text-[12px] text-[#999999] dark:text-zinc-500 mb-2">{{ __('Current status') }}</div>
                    <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-semibold {{ $this->order->status->color() }}">
                        {{ $this->order->status->label() }}
                    </span>
                </div>

                @if (count($this->availableTransitions) > 0)
                    <div class="space-y-4">
                        <div>
                            <label class="text-[13px] font-medium text-[#0d0d0d] dark:text-white mb-1.5 block">{{ __('New status') }}</label>
                            <select
                                wire:model="newStatus"
                                class="w-full px-4 py-2.5 text-[14px] rounded-[12px] border border-black/[0.08] dark:border-white/[0.08] bg-white dark:bg-zinc-900 text-[#0d0d0d] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#0fa76e]/30 focus:border-[#0fa76e]/50 transition-all"
                            >
                                <option value="">{{ __('Select new status…') }}</option>
                                @foreach ($this->availableTransitions as $transition)
                                    <option value="{{ $transition->value }}">{{ $transition->label() }}</option>
                                @endforeach
                            </select>
                            @error('newStatus')
                                <div class="mt-1 text-[12px] text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <button
                            wire:click="updateStatus"
                            wire:confirm="{{ __('Are you sure you want to change the order status?') }}"
                            wire:loading.attr="disabled"
                            class="w-full bg-[#0d0d0d] dark:bg-[#18E299] hover:opacity-80 disabled:opacity-50 text-white dark:text-[#0d0d0d] text-[14px] font-medium px-6 py-2.5 rounded-full transition-all"
                        >
                            <span wire:loading.remove wire:target="updateStatus">{{ __('Update status') }}</span>
                            <span wire:loading wire:target="updateStatus">{{ __('Updating…') }}</span>
                        </button>
                    </div>
                @else
                    <p class="text-[13px] text-[#999999] dark:text-zinc-500 italic">
                        {{ __('No further status changes possible for this order.') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Order items table --}}
        <div class="rounded-[16px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 overflow-hidden">
            <div class="px-6 py-4 border-b border-black/[0.05] dark:border-white/[0.06]">
                <h2 class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase">{{ __('Order items') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[14px] text-left">
                    <thead class="border-b border-black/[0.05] dark:border-white/[0.06]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Product') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Unit price') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Qty') }}</th>
                            <th scope="col" class="px-6 py-4 text-right text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.04] dark:divide-white/[0.04]">
                        @foreach ($this->order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4 font-medium text-[#0d0d0d] dark:text-white">
                                    {{ $item->product_name }}
                                </td>
                                <td class="px-6 py-4 text-[#666666] dark:text-zinc-400 font-mono">
                                    €{{ number_format((float) $item->unit_price, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-[#666666] dark:text-zinc-400 font-mono">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-[#0d0d0d] dark:text-white font-mono">
                                    €{{ number_format((float) $item->unit_price * $item->quantity, 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-black/[0.08] dark:border-white/[0.10]">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-[13px] font-medium text-[#666666] dark:text-zinc-400 uppercase tracking-wider">
                                {{ __('Total') }}
                            </td>
                            <td class="px-6 py-4 text-right text-[16px] font-semibold text-[#0d0d0d] dark:text-white font-mono">
                                {{ $this->order->formatted_total }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
