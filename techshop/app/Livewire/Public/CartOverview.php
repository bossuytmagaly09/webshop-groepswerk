<?php

namespace App\Livewire\Public;

use App\Services\CartService;
use Livewire\Component;

class CartOverview extends Component
{
    public function updateQuantity(CartService $cartService, $itemId, $productId, $quantity)
    {
        $cartService->updateQuantity($itemId, $productId, $quantity);

        $this->dispatch('cart-updated');
    }

    public function removeItem(CartService $cartService, $itemId, $productId)
    {
        $cartService->removeItem($itemId, $productId);

        $this->dispatch('cart-updated');
    }

    public function render(CartService $cartService)
    {
        $items = $cartService->getCartItems();
        $total = $cartService->getTotal();
        $vat = $total * 0.21;
        $grandTotal = $total + $vat;

        return view('livewire.public.cart-overview', [
            'items' => $items,
            'total' => $total,
            'vat' => $vat,
            'grandTotal' => $grandTotal,
        ])->layout('layouts.shop');
    }
}
