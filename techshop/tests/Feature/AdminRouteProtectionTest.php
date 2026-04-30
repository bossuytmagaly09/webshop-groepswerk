<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guest is redirected to login for admin dashboard', function () {
    get(route('dashboard'))->assertRedirect(route('login'));
});

test('non-admin user gets 403 on admin dashboard', function () {
    $customer = User::factory()->create();

    actingAs($customer)
        ->get(route('dashboard'))
        ->assertForbidden();
});

test('admin user can access the dashboard', function () {
    $admin = User::factory()->admin()->create();

    actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk();
});

test('non-admin user gets 403 on admin product manager', function () {
    $customer = User::factory()->create();

    actingAs($customer)
        ->get(route('dashboard.products'))
        ->assertForbidden();
});

test('non-admin user gets 403 on admin category manager', function () {
    $customer = User::factory()->create();

    actingAs($customer)
        ->get(route('dashboard.categories'))
        ->assertForbidden();
});

test('non-admin user gets 403 on admin user manager', function () {
    $customer = User::factory()->create();

    actingAs($customer)
        ->get(route('dashboard.users'))
        ->assertForbidden();
});

test('non-admin user gets 403 on admin orders page', function () {
    $customer = User::factory()->create();

    actingAs($customer)
        ->get(route('dashboard.orders'))
        ->assertForbidden();
});

test('admin can access all dashboard routes', function (string $routeName) {
    $admin = User::factory()->admin()->create();

    actingAs($admin)
        ->get(route($routeName))
        ->assertOk();
})->with([
    'dashboard',
    'dashboard.products',
    'dashboard.categories',
    'dashboard.users',
    'dashboard.orders',
]);
