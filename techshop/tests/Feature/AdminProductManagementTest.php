<?php

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin can create a product via action', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $action = new CreateProductAction;

    $product = $action->handle([
        'name' => 'New Gaming Mouse',
        'category_id' => $category->id,
        'description' => 'A premium gaming mouse',
        'price' => 79.99,
        'stock' => 25,
    ], null);

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->name)->toBe('New Gaming Mouse')
        ->and($product->slug)->toBe('new-gaming-mouse')
        ->and((float) $product->price)->toBe(79.99)
        ->and($product->stock)->toBe(25);

    $this->assertModelExists($product);
});

test('admin can create a product with an image', function () {
    Storage::fake('public');

    $category = Category::factory()->create();
    $action = new CreateProductAction;
    $image = UploadedFile::fake()->image('mouse.jpg', 400, 400);

    $product = $action->handle([
        'name' => 'Mouse With Image',
        'category_id' => $category->id,
        'description' => 'Has an image',
        'price' => 59.99,
        'stock' => 10,
    ], $image);

    expect($product->image)->not->toBeNull();
    Storage::disk('public')->assertExists($product->image);
});

test('admin can update an existing product via action', function () {
    $product = Product::factory()->for(Category::factory())->create([
        'name' => 'Old Name',
        'price' => 100.00,
    ]);

    $action = new UpdateProductAction;

    $updated = $action->handle($product, [
        'name' => 'Updated Name',
        'price' => 129.99,
    ], null);

    expect($updated->name)->toBe('Updated Name')
        ->and($updated->slug)->toBe('updated-name')
        ->and((float) $updated->price)->toBe(129.99);
});

test('admin can soft delete a product', function () {
    $product = Product::factory()->for(Category::factory())->create();

    $product->delete();

    $this->assertSoftDeleted('products', ['id' => $product->id]);

    // Product should still be findable with trashed
    expect(Product::withTrashed()->find($product->id))->not->toBeNull();
});

test('soft deleted product is excluded from default queries', function () {
    $product = Product::factory()->for(Category::factory())->create();

    $product->delete();

    expect(Product::find($product->id))->toBeNull();
});
