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
        // Verify the order has actually been checked out
        abort_if(is_null($order->checked_out_at), 404);

        // Auth users may only view their own orders
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        $sessionId = request()->query('session_id');

        // No session_id parameter — cannot verify payment
        abort_if(empty($sessionId), 404);

        // Verify the session_id matches the order's stored session_id
        abort_if($order->stripe_session_id !== $sessionId, 403);

        // Server-side verification with Stripe API
        $verifiedOrder = $verifyPayment->execute($sessionId);
        
        // We show the page regardless, but the status will reflect the outcome
        $this->order = $verifiedOrder ?? $order->fresh();
    }

    public function render(): View
    {
        return view('livewire.public.checkout-success')
            ->layout('layouts.shop');
    }
}
