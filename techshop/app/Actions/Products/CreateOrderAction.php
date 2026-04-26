<?php

namespace App\Actions\Products;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CreateOrderAction
{
    /**
     * Verwerkt een collectie aan (winkelwagen)items tot een daadwerkelijke bestelling.
     * Maakt gebruik van de "snapshot"-architectuur.
     */
    public function execute(Collection $cartItems, ?int $userId = null): Order
    {
        $userId = $userId ?? Auth::id();

        // Stap 1: Nieuwe order aanmaken. Conform instructies initieel op 'pending'.
        $order = Order::create([
            'user_id' => $userId,
            'status' => 'pending',
            'total_price' => 0,
        ]);

        $totalPrice = 0;

        foreach ($cartItems as $item) {
            // Snapshot architectuur: Sla data zoals deze NU is veilig op voor de factuur/historie.
            $snapshotName = $item->product ? $item->product->name : ($item->product_name ?? 'Onbekend Product');
            $snapshotPrice = $item->product ? $item->product->price : ($item->unit_price ?? 0);
            $quantity = $item->quantity;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $snapshotName,  // Snapshot van de naam
                'quantity' => $quantity,
                'unit_price' => $snapshotPrice,   // Snapshot van de prijs
            ]);

            $totalPrice += ($quantity * $snapshotPrice);
        }

        // Stap 3: Berekend eindtotaal terug opslaan in de gemaakte order
        $order->update(['total_price' => $totalPrice]);

        return $order;
    }
}
