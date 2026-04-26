<?php

namespace App\Actions\Cart;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AddItemToCartAction
{
    /**
     * Handelt logica af om een product aan de winkelwagen toe te voegen.
     * Slaat op in de database via Order (als ingelogd) of in Sessie (gasten).
     */
    public function execute(Product $product, int $quantity = 1): void
    {
        if (Auth::check()) {
            // DB Opslag: we gebruiken een 'pending' Order als winkelwagentje
            $order = Order::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'pending'],
                ['total_price' => 0]
            );

            $orderItem = OrderItem::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->first();

            if ($orderItem) {
                // Verhoog kwantiteit
                $orderItem->increment('quantity', $quantity);
            } else {
                // Nieuw order item aanmaken
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                ]);
            }
        } else {
            // Sessie Opslag - voor gasten
            $cart = Session::get('cart', []);
            
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $quantity;
            } else {
                $cart[$product->id] = [
                    'quantity' => $quantity,
                ];
            }
            
            Session::put('cart', $cart);
        }
    }
}
