<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Haalt actuele items op, geünificeerd voor DB en Session.
     */
    public function getCartItems()
    {
        if (Auth::check()) {
            $order = Order::where('user_id', Auth::id())->where('status', 'pending')->with('orderItems.product')->first();

            return $order ? $order->orderItems : collect();
        }

        $sessionCart = Session::get('cart', []);
        $items = collect();

        foreach ($sessionCart as $productId => $data) {
            $product = Product::find($productId);
            if ($product) {
                // Simuleer object structuur zoals eloquent voor de view
                $items->push((object) [
                    'id' => 'session_'.$productId,
                    'product_id' => $productId,
                    'product' => $product,
                    'quantity' => $data['quantity'],
                    'unit_price' => $product->price,
                ]);
            }
        }

        return $items;
    }

    public function updateQuantity($id, $productId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeItem($id, $productId);

            return;
        }

        if (Auth::check()) {
            OrderItem::where('id', $id)->update(['quantity' => $quantity]);
        } else {
            $cart = Session::get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
                Session::put('cart', $cart);
            }
        }
    }

    public function removeItem($id, $productId)
    {
        if (Auth::check()) {
            OrderItem::where('id', $id)->delete();
        } else {
            $cart = Session::get('cart', []);
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }
    }

    public function getTotal()
    {
        return $this->getCartItems()->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }

    /**
     * Totaal aantal stuks in de cart, geünificeerd voor DB en Sessie.
     */
    public function itemCount(): int
    {
        return (int) $this->getCartItems()->sum('quantity');
    }

    /**
     * Voeg de sessie-cart van een gast samen met de database-cart van een ingelogde gebruiker.
     * Bij overlappende producten worden de kwantiteiten opgeteld (de gast voegde net iets toe en wil het erbij).
     * De sessie-cart wordt geleegd na de merge.
     */
    public function mergeSessionCartIntoDatabase(int $userId): void
    {
        $sessionCart = Session::get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        $order = Order::firstOrCreate(
            ['user_id' => $userId, 'status' => 'pending'],
            ['total_price' => 0]
        );

        foreach ($sessionCart as $productId => $data) {
            $product = Product::find($productId);
            if (! $product) {
                continue;
            }

            $quantity = (int) ($data['quantity'] ?? 0);
            if ($quantity < 1) {
                continue;
            }

            $orderItem = OrderItem::where('order_id', $order->id)
                ->where('product_id', $productId)
                ->first();

            if ($orderItem) {
                $orderItem->increment('quantity', $quantity);
            } else {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                ]);
            }
        }

        Session::forget('cart');
    }
}
