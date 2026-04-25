<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('the homepage uses the shop layout and renders the navigation', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSeeText('TECH');
    $response->assertSeeText('SHOP');
    $response->assertSeeText('Products');
    $response->assertSee('Shop Now', false);
});

test('navigation lists existing categories dynamically', function () {
    Category::factory()->create(['name' => 'Workstations', 'slug' => 'workstations']);
    Category::factory()->create(['name' => 'Keyboards', 'slug' => 'keyboards']);

    Livewire::test('shop-navigation')
        ->assertSee('Workstations')
        ->assertSee('Keyboards')
        ->assertSee('/categories/workstations', false)
        ->assertSee('/categories/keyboards', false);
});

test('navigation handles an empty category list gracefully', function () {
    Livewire::test('shop-navigation')
        ->assertOk()
        ->assertSee('Products');
});
