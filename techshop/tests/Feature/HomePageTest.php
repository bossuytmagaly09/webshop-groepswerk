<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('homepage renders all main sections', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Browse Collection');
    $response->assertSee('Hardware that gets out of your way.');
    $response->assertSee('id="categories"', false);
    $response->assertSee('id="new-arrivals"', false);
});

test('homepage lists every category and links to filtered catalog by slug', function () {
    Category::factory()->create(['name' => 'Workstations', 'slug' => 'workstations']);
    Category::factory()->create(['name' => 'Keyboards', 'slug' => 'keyboards']);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSeeText('Workstations');
    $response->assertSeeText('Keyboards');
    $response->assertSee(route('products').'?category=workstations', false);
    $response->assertSee(route('products').'?category=keyboards', false);
});

test('homepage shows the four newest available products', function () {
    $category = Category::factory()->create();

    $older = Product::factory()->for($category)->create([
        'name' => 'Older Product',
        'stock' => 5,
        'created_at' => now()->subWeek(),
    ]);

    collect(range(1, 5))->each(fn ($i) => Product::factory()->for($category)->create([
        'name' => "Newest Product {$i}",
        'stock' => 10,
        'created_at' => now()->subMinutes(5 - $i),
    ]));

    $response = $this->get(route('home'));

    $response->assertOk();
    foreach ([2, 3, 4, 5] as $shown) {
        $response->assertSeeText("Newest Product {$shown}");
    }
    $response->assertDontSeeText('Newest Product 1');
    $response->assertDontSeeText($older->name);
});

test('homepage skips out-of-stock products in new arrivals', function () {
    $category = Category::factory()->create();

    Product::factory()->for($category)->create([
        'name' => 'Sold Out Item',
        'stock' => 0,
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertDontSeeText('Sold Out Item');
});

test('product newest scope orders by most recent and limits results', function () {
    $category = Category::factory()->create();

    Product::factory()->for($category)->count(3)->sequence(
        ['created_at' => now()->subDays(3)],
        ['created_at' => now()->subDays(2)],
        ['created_at' => now()->subDays(1)],
    )->create(['stock' => 10]);

    $results = Product::query()->newest(2)->get();

    expect($results)->toHaveCount(2);
});

test('product formatted_price accessor returns euro string', function () {
    $product = Product::factory()->make(['price' => 1234.5]);

    expect($product->formatted_price)->toBe('€ 1.234,50');
});

test('inCategory scope filters by slug', function () {
    $keyboards = Category::factory()->create(['slug' => 'keyboards']);
    $monitors = Category::factory()->create(['slug' => 'monitors']);

    Product::factory()->for($keyboards)->create(['name' => 'K1', 'stock' => 1]);
    Product::factory()->for($monitors)->create(['name' => 'M1', 'stock' => 1]);

    $results = Product::query()->inCategory('keyboards')->get();

    expect($results->pluck('name')->all())->toBe(['K1']);
});
