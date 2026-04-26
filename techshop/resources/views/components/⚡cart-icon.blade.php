<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function cartCount(): int
    {
        $cart = session('cart', []);

        return array_sum(array_column($cart, 'quantity'));
    }

    #[On('cart-updated')]
    public function refresh(): void
    {
        unset($this->cartCount);
    }
};
?>

<a href="/cart" class="relative inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="8" cy="21" r="1"/>
        <circle cx="19" cy="21" r="1"/>
        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
    </svg>

    @if ($this->cartCount > 0)
        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-[#18E299] text-[#0d0d0d] text-[10px] font-bold rounded-full leading-none">
            {{ $this->cartCount > 99 ? '99+' : $this->cartCount }}
        </span>
    @endif
</a>
