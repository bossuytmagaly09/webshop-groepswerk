<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the homepage uses the shop layout and renders the navigation', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSeeText('TECH');
    $response->assertSeeText('SHOP');
    $response->assertSeeText('Shop'); // Link in the navigation
});
