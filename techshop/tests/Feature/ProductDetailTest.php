<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('product detail page renders for an existing slug', function () {
    $product = Product::factory()->for(Category::factory()->create(['name' => 'Keyboards', 'slug' => 'keyboards']))->create([
        'name' => 'Halcyon 65',
        'slug' => 'halcyon-65',
        'description' => 'A compact mechanical keyboard.',
        'price' => 199.00,
        'stock' => 8,
    ]);

    $response = $this->get('/products/'.$product->slug);

    $response->assertOk();
    $response->assertSeeText('Halcyon 65');
    $response->assertSeeText('A compact mechanical keyboard.');
    $response->assertSeeText('Keyboards');
    $response->assertSeeText('In stock');
    $response->assertSeeText('Add to Cart');
});

test('product detail returns 404 for unknown slugs', function () {
    $this->get('/products/does-not-exist')->assertNotFound();
});

test('product detail returns 404 for soft-deleted products', function () {
    $product = Product::factory()->for(Category::factory())->create(['slug' => 'gone-soon']);
    $product->delete();

    $this->get('/products/gone-soon')->assertNotFound();
});

test('product detail shows low stock warning when stock is at or below 5', function () {
    $product = Product::factory()->for(Category::factory())->create([
        'slug' => 'low-stock-item',
        'stock' => 3,
    ]);

    $this->get('/products/'.$product->slug)
        ->assertOk()
        ->assertSeeText('Low stock')
        ->assertSeeText('only 3 left');
});

test('product detail disables add to cart and shows out-of-stock when stock is zero', function () {
    $product = Product::factory()->for(Category::factory())->create([
        'slug' => 'sold-out',
        'stock' => 0,
    ]);

    $response = $this->get('/products/'.$product->slug);

    $response->assertOk();
    $response->assertSeeText('Out of stock');
    $response->assertSeeText('Out of Stock');
    $response->assertDontSeeText('Add to Cart');
});

test('add-to-cart action stores guest cart in session and dispatches cart-updated event', function () {
    $product = Product::factory()->for(Category::factory())->create([
        'slug' => 'cart-target',
        'stock' => 10,
    ]);

    Livewire::test('pages::product-detail', ['product' => $product])
        ->call('increment')
        ->call('addToCart')
        ->assertDispatched('cart-updated');

    expect(session('cart'))->toHaveKey($product->id);
    expect(session('cart')[$product->id]['quantity'])->toBe(2);
});

test('add-to-cart validates that quantity does not exceed stock', function () {
    $product = Product::factory()->for(Category::factory())->create([
        'slug' => 'tight-stock',
        'stock' => 2,
    ]);

    Livewire::test('pages::product-detail', ['product' => $product])
        ->set('quantity', 5)
        ->call('addToCart')
        ->assertHasErrors(['quantity']);

    expect(session('cart', []))->toBe([]);
});
