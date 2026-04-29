<?php

namespace App\Actions\Checkout;

use App\Enums\OrderStatus;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Services\StripeService;
use Stripe\Exception\ApiErrorException;

class VerifyPaymentAction
{
    public function __construct(
        private StripeService $stripeService
    ) {}

    /**
     * Verify payment status with Stripe and update the order accordingly.
     *
     * Returns the order if verification succeeds (paid), null otherwise.
     */
    public function execute(string $sessionId): ?Order
    {
        $order = Order::where('stripe_session_id', $sessionId)->first();

        if (! $order) {
            return null;
        }

        // Already verified — don't hit Stripe again (idempotent)
        if ($order->status === OrderStatus::PAID) {
            return $order;
        }

        // Only pending orders can transition to paid
        if ($order->status !== OrderStatus::PENDING) {
            return null;
        }

        try {
            $session = $this->stripeService->retrieveCheckoutSession($sessionId);
        } catch (ApiErrorException) {
            return null;
        }

        if (! $this->stripeService->isSessionPaid($session)) {
            return null;
        }

        $order->update([
            'status' => OrderStatus::PAID,
            'stripe_payment_intent_id' => $session->payment_intent,
        ]);

        OrderPaid::dispatch($order->fresh());

        return $order->fresh();
    }
}
