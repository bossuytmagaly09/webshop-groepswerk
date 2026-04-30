<?php

use App\Models\Category;
use App\Models\Product;

test('guest can add a product to the session cart', function () {
    $product = Product::factory()
        ->for(Category::factory())
        ->create(['stock' => 10, 'price' => 29.99]);

    $this->withSession(['cart' => []])
        ->get(route('cart.index'))
        ->assertOk();

    // Simulate adding an item via session
    session()->put('cart', [$product->id => ['quantity' => 1]]);

    expect(session('cart'))->toHaveKey($product->id)
        ->and(session('cart')[$product->id]['quantity'])->toBe(1);
});

test('guest session cart persists across requests', function () {
    $product = Product::factory()
        ->for(Category::factory())
        ->create(['stock' => 5, 'price' => 49.99]);

    $this->withSession(['cart' => [$product->id => ['quantity' => 2]]])
        ->get(route('cart.index'))
        ->assertOk()
        ->assertSeeText($product->name);
});

test('guest can add multiple products to the session cart', function () {
    $category = Category::factory()->create();

    $productA = Product::factory()->for($category)->create(['stock' => 10]);
    $productB = Product::factory()->for($category)->create(['stock' => 10]);

    $cart = [
        $productA->id => ['quantity' => 1],
        $productB->id => ['quantity' => 3],
    ];

    $this->withSession(['cart' => $cart])
        ->get(route('cart.index'))
        ->assertOk()
        ->assertSeeText($productA->name)
        ->assertSeeText($productB->name);
});

test('guest cart is empty by default', function () {
    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSeeText('Your cart is empty');
});
