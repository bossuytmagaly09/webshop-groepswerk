<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeService
{
    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe Checkout Session for the given order.
     *
     * @param  array<int, array{name: string, quantity: int, unit_price: float}>  $lineItems
     * @return object{id: string, url: string}
     *
     * @throws ApiErrorException
     */
    public function createCheckoutSession(Order $order, array $lineItems, string $successUrl, string $cancelUrl): object
    {
        $stripeLineItems = array_map(fn (array $item): array => [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item['name'],
                ],
                'unit_amount' => (int) round($item['unit_price'] * 100),
            ],
            'quantity' => $item['quantity'],
        ], $lineItems);

        return $this->client->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => $stripeLineItems,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => (string) $order->id,
            'customer_email' => $order->email,
        ]);
    }

    /**
     * Retrieve a Checkout Session from Stripe and verify payment status.
     *
     * @return object{payment_status: string, payment_intent: string|null}
     *
     * @throws ApiErrorException
     */
    public function retrieveCheckoutSession(string $sessionId): object
    {
        return $this->client->checkout->sessions->retrieve($sessionId);
    }

    /**
     * Check if a Checkout Session has been paid.
     *
     * @param  object{payment_status: string}  $session
     */
    public function isSessionPaid(object $session): bool
    {
        return $session->payment_status === 'paid';
    }
}
