<?php

namespace App\Livewire\Public;

use App\Actions\Checkout\CheckoutAction;
use App\Livewire\Forms\CheckoutForm;
use App\Services\CartService;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Checkout extends Component
{
    public CheckoutForm $form;

    public function mount(CartService $cartService): void
    {
        if ($cartService->getCartItems()->isEmpty()) {
            $this->redirectRoute('cart.index');

            return;
        }

        if (auth()->check()) {
            $user = auth()->user();
            $this->form->email = $user->email;

            $nameParts = explode(' ', $user->name, 2);
            $this->form->firstName = $nameParts[0] ?? '';
            $this->form->lastName = $nameParts[1] ?? '';
        }
    }

    #[Computed]
    public function cartItems(): Collection
    {
        return app(CartService::class)->getCartItems();
    }

    #[Computed]
    public function subtotal(): float
    {
        return (float) $this->cartItems->sum(fn ($item) => $item->quantity * $item->unit_price);
    }

    #[Computed]
    public function shipping(): float
    {
        return 0.0;
    }

    #[Computed]
    public function total(): float
    {
        return $this->subtotal + $this->shipping;
    }

    public function submit(CheckoutAction $action): void
    {
        $this->form->validate();

        $result = $action->execute($this->form, $this->cartItems);

        $this->redirect($result['stripe_url']);
    }

    public function render(): View
    {
        return view('livewire.public.checkout')
            ->layout('layouts.shop');
    }
}
