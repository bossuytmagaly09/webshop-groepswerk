<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use InvalidArgumentException;

class UpdateOrderStatusAction
{
    public function handle(Order $order, OrderStatus $newStatus): void
    {
        if (! in_array($newStatus, $order->status->allowedTransitions())) {
            throw new InvalidArgumentException(
                "Cannot transition from {$order->status->value} to {$newStatus->value}."
            );
        }

        $order->update(['status' => $newStatus->value]);
    }
}
