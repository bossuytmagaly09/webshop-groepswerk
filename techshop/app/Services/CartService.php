<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
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
                $items->push((object)[
                    'id' => 'session_' . $productId,
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
        return $this->getCartItems()->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}
