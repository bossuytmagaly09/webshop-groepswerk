<?php

use App\Actions\Products\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('CreateOrderAction creates order with pending status', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for(Category::factory())->create([
        'price' => 75.00,
        'name' => 'Unit Test Widget',
    ]);

    $cartItems = collect([
        (object) [
            'product_id' => $product->id,
            'product' => $product,
            'product_name' => $product->name,
            'quantity' => 2,
            'unit_price' => $product->price,
        ],
    ]);

    $action = new CreateOrderAction;
    $order = $action->execute($cartItems, $user->id);

    expect($order->status)->toBe(OrderStatus::PENDING)
        ->and($order->status)->not->toBe(OrderStatus::PAID);
});

test('CreateOrderAction creates order items with correct snapshots', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for(Category::factory())->create([
        'price' => 42.50,
        'name' => 'Snapshot Product',
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
    $order->load('orderItems');

    expect($order->orderItems)->toHaveCount(1);

    $item = $order->orderItems->first();

    expect($item->product_name)->toBe('Snapshot Product')
        ->and((float) $item->unit_price)->toBe(42.50)
        ->and($item->quantity)->toBe(3)
        ->and($item->product_id)->toBe($product->id);
});

test('CreateOrderAction calculates total price correctly', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $productA = Product::factory()->for($category)->create(['price' => 10.00]);
    $productB = Product::factory()->for($category)->create(['price' => 25.50]);

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

    // 2 * 10.00 + 1 * 25.50 = 45.50
    expect((float) $order->total_price)->toBe(45.50);
});

test('CreateOrderAction assigns correct user id', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for(Category::factory())->create(['price' => 15.00]);

    $cartItems = collect([
        (object) [
            'product_id' => $product->id,
            'product' => $product,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => $product->price,
        ],
    ]);

    $action = new CreateOrderAction;
    $order = $action->execute($cartItems, $user->id);

    expect($order->user_id)->toBe($user->id);
});
