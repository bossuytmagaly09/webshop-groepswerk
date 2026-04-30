<?php

use App\Events\OrderPaid;
use App\Listeners\SendOrderConfirmationEmail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->product = Product::factory()->create(['price' => 29.99, 'name' => 'Wireless Mouse']);
});

test('order paid event has a queued listener for confirmation email', function () {
    Event::fake();

    Event::assertListening(
        OrderPaid::class,
        SendOrderConfirmationEmail::class,
    );
});

test('listener sends order confirmation email', function () {
    Mail::fake();

    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'email' => 'buyer@example.com',
        'status' => 'paid',
        'total_price' => 59.98,
        'checked_out_at' => now(),
        'shipping_first_name' => 'Jan',
        'shipping_last_name' => 'Peeters',
        'shipping_address_line_1' => 'Kerkstraat 1',
        'shipping_postcode' => '2000',
        'shipping_city' => 'Antwerpen',
        'shipping_country' => 'België',
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'product_name' => $this->product->name,
        'quantity' => 2,
        'unit_price' => $this->product->price,
    ]);

    $order->load('orderItems');

    $listener = app(SendOrderConfirmationEmail::class);
    $listener->handle(new OrderPaid($order));

    Mail::assertSent(OrderConfirmationMail::class, function (OrderConfirmationMail $mail) use ($order) {
        return $mail->hasTo('buyer@example.com')
            && $mail->order->id === $order->id;
    });
});

test('confirmation email contains order details', function () {
    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'email' => 'buyer@example.com',
        'status' => 'paid',
        'total_price' => 59.98,
        'checked_out_at' => now(),
        'shipping_first_name' => 'Jan',
        'shipping_last_name' => 'Peeters',
        'shipping_address_line_1' => 'Kerkstraat 1',
        'shipping_postcode' => '2000',
        'shipping_city' => 'Antwerpen',
        'shipping_country' => 'België',
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'product_name' => 'Wireless Mouse',
        'quantity' => 2,
        'unit_price' => 29.99,
    ]);

    $order->load('orderItems');

    $mailable = new OrderConfirmationMail($order);

    $mailable->assertSeeInHtml('Wireless Mouse');
    $mailable->assertSeeInHtml('€59.98');
    $mailable->assertSeeInHtml('Jan');
    $mailable->assertSeeInHtml('Kerkstraat 1');
    $mailable->assertSeeInHtml('Antwerpen');
});

test('confirmation email has correct subject line', function () {
    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'email' => 'buyer@example.com',
        'status' => 'paid',
        'total_price' => 29.99,
        'checked_out_at' => now(),
    ]);

    $mailable = new OrderConfirmationMail($order);
    $orderNumber = str_pad($order->id, 6, '0', STR_PAD_LEFT);

    $mailable->assertHasSubject("Order confirmation — #{$orderNumber}");
});

test('listener implements should queue', function () {
    $listener = new SendOrderConfirmationEmail;

    expect($listener)->toBeInstanceOf(ShouldQueue::class);
});

test('dispatching order paid event triggers email', function () {
    Mail::fake();

    // Force sync queue so the ShouldQueue listener executes immediately
    config(['queue.default' => 'sync']);

    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'email' => 'fullflow@example.com',
        'status' => 'paid',
        'total_price' => 29.99,
        'checked_out_at' => now(),
    ]);

    $order->load('orderItems');

    OrderPaid::dispatch($order);

    Mail::assertSent(OrderConfirmationMail::class, function (OrderConfirmationMail $mail) {
        return $mail->hasTo('fullflow@example.com');
    });
});
