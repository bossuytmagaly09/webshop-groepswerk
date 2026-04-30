<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Policies\OrderPolicy;

test('user can view their own order via policy', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create(['status' => OrderStatus::PAID->value]);

    $policy = new OrderPolicy;

    expect($policy->view($user, $order))->toBeTrue();
});

test('user cannot view another users order via policy', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $order = Order::factory()->for($userA)->create(['status' => OrderStatus::PAID->value]);

    $policy = new OrderPolicy;

    expect($policy->view($userB, $order))->toBeFalse();
});

test('admin can view any order via policy', function () {
    $admin = User::factory()->admin()->create();
    $customer = User::factory()->create();
    $order = Order::factory()->for($customer)->create(['status' => OrderStatus::PAID->value]);

    $policy = new OrderPolicy;

    expect($policy->view($admin, $order))->toBeTrue();
});

test('user can see their own orders on the my-orders page', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create(['status' => OrderStatus::PAID->value]);
    OrderItem::factory()->for($order)->create();

    $this->actingAs($user)
        ->get(route('my-orders'))
        ->assertOk()
        ->assertSee('#'.$order->id);
});

test('user cannot see orders of another user on the my-orders page', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $orderOfA = Order::factory()->for($userA)->create(['status' => OrderStatus::PAID->value]);
    OrderItem::factory()->for($orderOfA)->create(['product_name' => 'Secret Widget XJ9']);

    $this->actingAs($userB)
        ->get(route('my-orders'))
        ->assertOk()
        ->assertDontSee('Secret Widget XJ9');
});

test('only admin can view all orders via viewAny policy', function () {
    $admin = User::factory()->admin()->create();
    $customer = User::factory()->create();

    $policy = new OrderPolicy;

    expect($policy->viewAny($admin))->toBeTrue()
        ->and($policy->viewAny($customer))->toBeFalse();
});
