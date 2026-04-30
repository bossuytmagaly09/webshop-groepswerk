<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        $orderNumber = str_pad($this->order->id, 6, '0', STR_PAD_LEFT);

        return new Envelope(
            subject: "Order confirmation — #{$orderNumber}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.order-confirmation',
            with: [
                'order' => $this->order,
                'orderItems' => $this->order->orderItems,
                'orderNumber' => str_pad($this->order->id, 6, '0', STR_PAD_LEFT),
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
