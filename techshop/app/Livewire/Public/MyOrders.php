<?php

namespace App\Livewire\Public;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.shop')]
class MyOrders extends Component
{
    public function render()
    {
        // Haal alle orders van de momenteel ingelogde user op, gesorteerd van nieuw naar oud
        $orders = Auth::user()->orders()->with('items')->latest()->get();

        return view('livewire.public.my-orders', [
            'orders' => $orders
        ]);
    }
}
