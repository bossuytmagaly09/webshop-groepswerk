<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('session cart merges into a fresh database cart on login', function () {
    $product = Product::factory()->for(Category::factory())->create(['stock' => 10]);
    $user = User::factory()->create();

    session()->put('cart', [$product->id => ['quantity' => 2]]);

    event(new Login('web', $user, false));

    $order = Order::where('user_id', $user->id)->where('status', 'pending')->firstOrFail();
    expect($order->orderItems)->toHaveCount(1)
        ->and($order->orderItems->first()->quantity)->toBe(2)
        ->and(session('cart', []))->toBe([]);
});

test('session cart adds quantities to existing database cart items on login', function () {
    $product = Product::factory()->for(Category::factory())->create(['stock' => 10]);
    $user = User::factory()->create();

    $order = Order::create(['user_id' => $user->id, 'status' => 'pending', 'total_price' => 0]);
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => $product->price,
    ]);

    session()->put('cart', [$product->id => ['quantity' => 3]]);

    event(new Login('web', $user, false));

    expect($order->fresh()->orderItems->first()->quantity)->toBe(4)
        ->and(session('cart', []))->toBe([]);
});

test('item count uses database for authenticated users', function () {
    $product = Product::factory()->for(Category::factory())->create(['stock' => 10]);
    $user = User::factory()->create();

    $order = Order::create(['user_id' => $user->id, 'status' => 'pending', 'total_price' => 0]);
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 5,
        'unit_price' => $product->price,
    ]);

    $this->actingAs($user);

    expect(app(CartService::class)->itemCount())->toBe(5);
});

test('item count uses session for guests', function () {
    $product = Product::factory()->for(Category::factory())->create(['stock' => 10]);

    session()->put('cart', [$product->id => ['quantity' => 4]]);

    expect(app(CartService::class)->itemCount())->toBe(4);
});
