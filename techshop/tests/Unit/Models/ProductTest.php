<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('product scopeAvailable filters out products with zero stock', function () {
    // Arrange
    Product::factory()->create(['stock' => 10]);
    Product::factory()->create(['stock' => 1]);
    Product::factory()->create(['stock' => 0]);

    // Act
    $availableProducts = Product::available()->get();

    // Assert
    expect($availableProducts)->toHaveCount(2)
        ->and($availableProducts->pluck('stock'))->not->toContain(0);
});

test('product scopeAvailable includes products with positive stock', function () {
    // Arrange
    $product = Product::factory()->create(['stock' => 5]);

    // Act
    $availableProducts = Product::available()->get();

    // Assert
    expect($availableProducts->first()->id)->toBe($product->id);
});
