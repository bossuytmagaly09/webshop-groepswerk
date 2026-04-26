<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Services\CartService;
use App\Actions\Cart\AddItemToCartAction;

class CartOverview extends Component
{
    public function updateQuantity(CartService $cartService, $itemId, $productId, $quantity)
    {
        $cartService->updateQuantity($itemId, $productId, $quantity);
    }

    public function removeItem(CartService $cartService, $itemId, $productId)
    {
        $cartService->removeItem($itemId, $productId);
    }

    public function render(CartService $cartService)
    {
        $items = $cartService->getCartItems();
        $total = $cartService->getTotal();
        $vat = $total * 0.21; // 21% BTW calculatie
        $grandTotal = $total + $vat;

        return view('livewire.public.cart-overview', [
            'items' => $items,
            'total' => $total,
            'vat' => $vat,
            'grandTotal' => $grandTotal
        ])->layout('components.layouts.frontend'); // Aangepast naar frontend design layout
    }
}
