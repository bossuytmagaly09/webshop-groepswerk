<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cart page renders inside the main shop layout', function () {
    $response = $this->get(route('cart.index'));

    $response->assertOk();
    $response->assertSeeHtml('TECH<span class="text-[#18E299]">SHOP</span>');
    $response->assertSee('Built for creators');
});

test('cart page is fully translated to english', function () {
    $response = $this->get(route('cart.index'));

    $response->assertOk();
    $response->assertSeeText('Your cart');
    $response->assertSeeText('Review your order');
    $response->assertSeeText('Order summary');
    $response->assertSeeText('Subtotal (excl. VAT)');
    $response->assertSeeText('VAT (21%)');
    $response->assertSeeText('Total');
    $response->assertSeeText('Proceed to Checkout');

    $response->assertDontSeeText('Winkelwagen');
    $response->assertDontSeeText('Besteloverzicht');
    $response->assertDontSeeText('Subtotaal');
    $response->assertDontSeeText('Afrekenen');
    $response->assertDontSeeText('Verwijder');
});

test('empty cart shows english empty state and a browse cta', function () {
    $response = $this->get(route('cart.index'));

    $response->assertOk();
    $response->assertSeeText('Your cart is empty');
    $response->assertSeeText('Browse Collection');
});

test('cart page lists session items with remove and quantity controls', function () {
    $product = Product::factory()->for(Category::factory())->create([
        'name' => 'Mech Keyboard',
        'price' => 149.00,
        'stock' => 10,
    ]);

    $this->withSession(['cart' => [$product->id => ['quantity' => 2]]]);

    $response = $this->get(route('cart.index'));

    $response->assertOk();
    $response->assertSeeText('Mech Keyboard');
    $response->assertSeeText('Remove');
    $response->assertSeeText('per item');
});
