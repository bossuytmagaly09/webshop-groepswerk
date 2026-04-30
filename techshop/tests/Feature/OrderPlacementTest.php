<?php

use App\Actions\Products\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

test('authenticated user can place an order with pending status', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $productA = Product::factory()->for($category)->create(['price' => 25.00, 'stock' => 10]);
    $productB = Product::factory()->for($category)->create(['price' => 50.00, 'stock' => 5]);

    $cartItems = collect([
        (object) [
            'product_id' => $productA->id,
            'product' => $productA,
            'product_name' => $productA->name,
            'quantity' => 2,
            'unit_price' => $productA->price,
        ],
        (object) [
            'product_id' => $productB->id,
            'product' => $productB,
            'product_name' => $productB->name,
            'quantity' => 1,
            'unit_price' => $productB->price,
        ],
    ]);

    $action = new CreateOrderAction;
    $order = $action->execute($cartItems, $user->id);

    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->status)->toBe(OrderStatus::PENDING)
        ->and($order->user_id)->toBe($user->id)
        ->and((float) $order->total_price)->toBe(100.00);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'user_id' => $user->id,
        'status' => 'pending',
    ]);
});

test('order items are created with correct snapshot data', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for(Category::factory())->create([
        'price' => 99.99,
        'name' => 'Snapshot Widget',
    ]);

    $cartItems = collect([
        (object) [
            'product_id' => $product->id,
            'product' => $product,
            'product_name' => $product->name,
            'quantity' => 3,
            'unit_price' => $product->price,
        ],
    ]);

    $action = new CreateOrderAction;
    $order = $action->execute($cartItems, $user->id);

    $this->assertDatabaseHas('order_details', [
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => 'Snapshot Widget',
        'quantity' => 3,
        'unit_price' => 99.99,
    ]);
});
