<?php

namespace App\Livewire\Public;

use App\Actions\Checkout\VerifyPaymentAction;
use App\Models\Order;
use Illuminate\View\View;
use Livewire\Component;

class CheckoutSuccess extends Component
{
    public Order $order;

    public function mount(Order $order, VerifyPaymentAction $verifyPayment): void
    {
        abort_if(is_null($order->checked_out_at), 404);

        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        $sessionId = request()->query('session_id');

        abort_if(empty($sessionId), 404);

        abort_if($order->stripe_session_id !== $sessionId, 403);

        $verifiedOrder = $verifyPayment->execute($sessionId);

        $this->order = $verifiedOrder ?? $order->fresh();
    }

    public function render(): View
    {
        return view('livewire.public.checkout-success')
            ->layout('layouts.shop');
    }
}
