<?php

namespace App\Livewire\Public;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.shop')]
class MyOrders extends Component
{
    public function render()
    {
        $orders = Auth::user()->orders()
            ->with('orderItems.product')
            ->whereHas('orderItems')
            ->where('status', '!=', 'pending')
            ->latest()
            ->get();

        return view('livewire.public.my-orders', [
            'orders' => $orders,
        ]);
    }
}
