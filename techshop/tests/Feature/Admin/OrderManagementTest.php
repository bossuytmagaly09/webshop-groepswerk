<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->admin = User::factory()->create();
});

// --- Access control ---

test('guests are redirected to login for the orders index', function () {
    get(route('dashboard.orders'))->assertRedirect(route('login'));
});

test('guests are redirected to login for an order detail page', function () {
    $order = Order::factory()->create();
    get(route('dashboard.orders.show', $order))->assertRedirect(route('login'));
});

// --- Orders index ---

test('authenticated users can visit the orders index', function () {
    actingAs($this->admin)
        ->get(route('dashboard.orders'))
        ->assertOk();
});

test('orders index shows orders', function () {
    $order = Order::factory()->create(['status' => OrderStatus::PENDING->value]);

    actingAs($this->admin)
        ->get(route('dashboard.orders'))
        ->assertOk()
        ->assertSee(str_pad($order->id, 6, '0', STR_PAD_LEFT));
});

test('orders index filters by status', function () {
    $paid = Order::factory()->create(['status' => OrderStatus::PAID->value]);
    $cancelled = Order::factory()->create(['status' => OrderStatus::CANCELLED->value]);

    actingAs($this->admin);

    Livewire::test('pages::dashboard.orders')
        ->set('statusFilter', OrderStatus::PAID->value)
        ->assertSee(str_pad($paid->id, 6, '0', STR_PAD_LEFT))
        ->assertDontSee(str_pad($cancelled->id, 6, '0', STR_PAD_LEFT));
});

// --- Order detail ---

test('authenticated users can visit an order detail page', function () {
    $order = Order::factory()->create(['status' => OrderStatus::PENDING->value]);
    OrderItem::factory()->for($order)->create(['product_name' => 'Test Product']);

    actingAs($this->admin)
        ->get(route('dashboard.orders.show', $order))
        ->assertOk()
        ->assertSee(str_pad($order->id, 6, '0', STR_PAD_LEFT))
        ->assertSee('Test Product');
});

test('order detail shows customer info for registered user', function () {
    $customer = User::factory()->create(['name' => 'Jane Doe']);
    $order = Order::factory()->for($customer)->create(['status' => OrderStatus::PAID->value]);

    actingAs($this->admin)
        ->get(route('dashboard.orders.show', $order))
        ->assertOk()
        ->assertSee('Jane Doe');
});

// --- Status transitions via Livewire ---

test('admin can update order status with a valid transition', function () {
    $order = Order::factory()->create(['status' => OrderStatus::PENDING->value]);

    actingAs($this->admin);

    Livewire::test('pages::dashboard.order-detail', ['order' => $order])
        ->set('newStatus', OrderStatus::PAID->value)
        ->call('updateStatus')
        ->assertHasNoErrors();

    expect($order->fresh()->status)->toBe(OrderStatus::PAID);
});

test('admin cannot update order status with an invalid transition', function () {
    $order = Order::factory()->create(['status' => OrderStatus::CANCELLED->value]);

    actingAs($this->admin);

    expect(function () use ($order) {
        Livewire::test('pages::dashboard.order-detail', ['order' => $order])
            ->set('newStatus', OrderStatus::PENDING->value)
            ->call('updateStatus');
    })->toThrow(InvalidArgumentException::class);
});

test('validation fails when no new status is selected', function () {
    $order = Order::factory()->create(['status' => OrderStatus::PENDING->value]);

    actingAs($this->admin);

    Livewire::test('pages::dashboard.order-detail', ['order' => $order])
        ->set('newStatus', '')
        ->call('updateStatus')
        ->assertHasErrors(['newStatus']);
});

// --- OrderStatus enum ---

test('cancelled order has no allowed transitions', function () {
    expect(OrderStatus::CANCELLED->allowedTransitions())->toBeEmpty();
});

test('refunded order has no allowed transitions', function () {
    expect(OrderStatus::REFUNDED->allowedTransitions())->toBeEmpty();
});

test('pending order can transition to paid or cancelled', function () {
    expect(OrderStatus::PENDING->allowedTransitions())
        ->toContain(OrderStatus::PAID)
        ->toContain(OrderStatus::CANCELLED);
});

test('OrderStatus color returns a non-empty string for each case', function (OrderStatus $status) {
    expect($status->color())->toBeString()->not->toBeEmpty();
})->with(OrderStatus::cases());

test('OrderStatus label returns a non-empty string for each case', function (OrderStatus $status) {
    expect($status->label())->toBeString()->not->toBeEmpty();
})->with(OrderStatus::cases());
