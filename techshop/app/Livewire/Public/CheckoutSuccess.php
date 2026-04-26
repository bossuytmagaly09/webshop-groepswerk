<?php

namespace App\Livewire\Public;

use App\Models\Order;
use Illuminate\View\View;
use Livewire\Component;

class CheckoutSuccess extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        // Verify the order has actually been checked out
        abort_if(is_null($order->checked_out_at), 404);

        // Auth users may only view their own orders
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }
    }

    public function render(): View
    {
        return view('livewire.public.checkout-success')
            ->layout('layouts.shop');
    }
}
