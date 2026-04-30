<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Mail\OrderConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    /**
     * Handle the OrderPaid event by sending a confirmation email.
     */
    public function handle(OrderPaid $event): void
    {
        Mail::to($event->order->email)
            ->send(new OrderConfirmationMail($event->order));
    }
}
