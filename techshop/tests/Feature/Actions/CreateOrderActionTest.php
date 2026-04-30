<?php

use App\Actions\Products\CreateOrderAction;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

test('CreateOrderAction creates an order with correct items and total price', function () {
    // Arrange
    $user = User::factory()->create();
    $product1 = Product::factory()->create(['price' => 100, 'name' => 'Product 1']);
    $product2 = Product::factory()->create(['price' => 50, 'name' => 'Product 2']);

    $cartItems = collect([
        (object) [
            'product_id' => $product1->id,
            'product' => $product1,
            'quantity' => 2,
        ],
        (object) [
            'product_id' => $product2->id,
            'product' => $product2,
            'quantity' => 1,
        ],
    ]);

    $action = new CreateOrderAction;

    // Act
    $order = $action->execute($cartItems, $user->id);

    // Assert
    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->total_price)->toBe('250.00') // 2*100 + 1*50
        ->and($order->orderItems)->toHaveCount(2);

    $item1 = $order->orderItems->where('product_id', $product1->id)->first();
    expect($item1->product_name)->toBe('Product 1')
        ->and($item1->unit_price)->toBe('100.00')
        ->and($item1->quantity)->toBe(2);
});

test('CreateOrderAction uses snapshots of product names and prices', function () {
    // Arrange
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 100, 'name' => 'Original Name']);

    $cartItems = collect([
        (object) [
            'product_id' => $product->id,
            'product' => $product,
            'quantity' => 1,
        ],
    ]);

    $action = new CreateOrderAction;
    $order = $action->execute($cartItems, $user->id);

    // Change the product price/name after order creation
    $product->update(['price' => 200, 'name' => 'New Name']);

    // Assert
    $item = $order->orderItems->first();
    expect($item->product_name)->toBe('Original Name') // Should still be old name
        ->and($item->unit_price)->toBe('100.00');    // Should still be old price
});
