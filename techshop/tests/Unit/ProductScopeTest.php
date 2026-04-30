<?php

use App\Models\Category;
use App\Models\Product;

test('scopeAvailable returns only products with stock greater than zero', function () {
    $category = Category::factory()->create();

    $inStock = Product::factory()->for($category)->create(['stock' => 5]);
    $outOfStock = Product::factory()->for($category)->create(['stock' => 0]);

    $available = Product::available()->pluck('id');

    expect($available)->toContain($inStock->id)
        ->and($available)->not->toContain($outOfStock->id);
});

test('scopeAvailable excludes products with exactly zero stock', function () {
    $category = Category::factory()->create();

    Product::factory()->for($category)->create(['stock' => 0]);
    Product::factory()->for($category)->create(['stock' => 0]);
    $singleInStock = Product::factory()->for($category)->create(['stock' => 1]);

    $available = Product::available()->get();

    expect($available)->toHaveCount(1)
        ->and($available->first()->id)->toBe($singleInStock->id);
});

test('formattedPrice accessor returns European format with euro sign', function () {
    $product = Product::factory()->for(Category::factory())->create(['price' => 1299.50]);

    expect($product->formatted_price)->toBe('€ 1.299,50');
});

test('formattedPrice accessor handles single digit price', function () {
    $product = Product::factory()->for(Category::factory())->create(['price' => 5.00]);

    expect($product->formatted_price)->toBe('€ 5,00');
});

test('scopeSearch filters products by name', function () {
    $category = Category::factory()->create();

    $keyboard = Product::factory()->for($category)->create(['name' => 'Mechanical Keyboard']);
    $mouse = Product::factory()->for($category)->create(['name' => 'Gaming Mouse']);

    $results = Product::search('Keyboard')->pluck('id');

    expect($results)->toContain($keyboard->id)
        ->and($results)->not->toContain($mouse->id);
});

test('scopeSearch returns all products when search is empty', function () {
    $category = Category::factory()->create();

    Product::factory()->for($category)->count(3)->create();

    $results = Product::search('')->get();

    expect($results)->toHaveCount(3);
});

test('scopeInCategory filters products by category id', function () {
    $catA = Category::factory()->create();
    $catB = Category::factory()->create();

    $productInA = Product::factory()->for($catA)->create();
    $productInB = Product::factory()->for($catB)->create();

    $results = Product::inCategory((string) $catA->id)->pluck('id');

    expect($results)->toContain($productInA->id)
        ->and($results)->not->toContain($productInB->id);
});

test('scopeInCategory filters products by category slug', function () {
    $catA = Category::factory()->create(['slug' => 'keyboards']);
    $catB = Category::factory()->create(['slug' => 'mice']);

    $productInA = Product::factory()->for($catA)->create();
    $productInB = Product::factory()->for($catB)->create();

    $results = Product::inCategory('keyboards')->pluck('id');

    expect($results)->toContain($productInA->id)
        ->and($results)->not->toContain($productInB->id);
});
