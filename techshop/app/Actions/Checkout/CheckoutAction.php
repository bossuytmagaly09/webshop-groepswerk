<?php

namespace App\Actions\Checkout;

use App\Livewire\Forms\CheckoutForm;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\StripeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutAction
{
    public function __construct(
        private StripeService $stripeService
    ) {}

    /**
     * Create/finalize the order and start a Stripe Checkout Session.
     *
     * @return array{order: Order, stripe_url: string}
     */
    public function execute(CheckoutForm $form, Collection $cartItems): array
    {
        $shippingData = [
            'email' => $form->email,
            'shipping_first_name' => $form->firstName,
            'shipping_last_name' => $form->lastName,
            'shipping_address_line_1' => $form->addressLine1,
            'shipping_address_line_2' => $form->addressLine2,
            'shipping_postcode' => $form->postcode,
            'shipping_city' => $form->city,
            'shipping_country' => $form->country,
            'shipping_phone' => $form->phone,
            'checked_out_at' => now(),
        ];

        if (Auth::check()) {
            $order = $this->checkoutAuthUser($shippingData, $cartItems);
        } else {
            $order = $this->checkoutGuest($shippingData, $cartItems);
        }

        $session = $this->createStripeSession($order);

        $order->update(['stripe_session_id' => $session->id]);

        return [
            'order' => $order,
            'stripe_url' => $session->url,
        ];
    }

    private function checkoutAuthUser(array $shippingData, Collection $cartItems): Order
    {
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->whereNull('checked_out_at')
            ->firstOrFail();

        // Recalculate server-side — never trust client prices
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $currentPrice = $item->product?->price ?? $item->unit_price;
            $currentName = $item->product?->name ?? ($item->product_name ?? 'Unknown Product');

            $order->orderItems()->where('id', $item->id)->update([
                'product_name' => $currentName,
                'unit_price' => $currentPrice,
            ]);

            $totalPrice += $item->quantity * $currentPrice;
        }

        $order->update(array_merge($shippingData, ['total_price' => $totalPrice]));

        return $order->fresh();
    }

    private function checkoutGuest(array $shippingData, Collection $cartItems): Order
    {
        $order = Order::create(array_merge($shippingData, [
            'user_id' => null,
            'status' => 'pending',
            'total_price' => 0,
        ]));

        $totalPrice = 0;

        foreach ($cartItems as $item) {
            // Recalculate server-side — never trust client prices
            $currentPrice = $item->product?->price ?? $item->unit_price;
            $currentName = $item->product?->name ?? ($item->product_name ?? 'Unknown Product');

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $currentName,
                'quantity' => $item->quantity,
                'unit_price' => $currentPrice,
            ]);

            $totalPrice += $item->quantity * $currentPrice;
        }

        $order->update(['total_price' => $totalPrice]);

        Session::forget('cart');

        return $order->fresh();
    }

    private function createStripeSession(Order $order): object
    {
        $order->load('orderItems');

        $lineItems = $order->orderItems->map(fn (OrderItem $item): array => [
            'name' => $item->product_name ?? 'Product',
            'quantity' => $item->quantity,
            'unit_price' => (float) $item->unit_price,
        ])->all();

        $successUrl = route('checkout.success', ['order' => $order->id]).'?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('checkout.index');

        return $this->stripeService->createCheckoutSession($order, $lineItems, $successUrl, $cancelUrl);
    }
}
