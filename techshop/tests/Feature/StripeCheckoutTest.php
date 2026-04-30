<?php

use App\Actions\Checkout\CheckoutAction;
use App\Actions\Checkout\VerifyPaymentAction;
use App\Enums\OrderStatus;
use App\Events\OrderPaid;
use App\Livewire\Forms\CheckoutForm;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Support\Facades\Event;
use Livewire\Component;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->product = Product::factory()->create(['price' => 49.99, 'name' => 'Test Widget']);

    // Bind a default mock so StripeService constructor (which needs an API key) never runs
    $this->app->instance(StripeService::class, Mockery::mock(StripeService::class));
});

function makeFakeStripeSession(string $id, string $paymentStatus = 'unpaid', ?string $paymentIntent = null): object
{
    return (object) [
        'id' => $id,
        'url' => "https://checkout.stripe.com/pay/{$id}",
        'payment_status' => $paymentStatus,
        'payment_intent' => $paymentIntent,
        'client_reference_id' => null,
    ];
}

test('checkout action creates order with pending status and redirects to stripe', function () {
    $this->actingAs($this->user);

    // Create a cart order for the authenticated user
    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
        'total_price' => 0,
        'checked_out_at' => null,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'product_name' => $this->product->name,
        'quantity' => 2,
        'unit_price' => $this->product->price,
    ]);

    $fakeSession = makeFakeStripeSession('cs_test_abc123');

    $mockStripeService = Mockery::mock(StripeService::class);
    $mockStripeService->shouldReceive('createCheckoutSession')
        ->once()
        ->andReturn($fakeSession);

    $this->app->instance(StripeService::class, $mockStripeService);

    $form = new CheckoutForm(
        Mockery::mock(Component::class),
        'form'
    );
    $form->email = 'test@example.com';
    $form->firstName = 'John';
    $form->lastName = 'Doe';
    $form->addressLine1 = 'Kerkstraat 1';
    $form->postcode = '1000';
    $form->city = 'Brussel';
    $form->country = 'België';

    $action = app(CheckoutAction::class);
    $result = $action->execute($form, $order->orderItems);

    expect($result)->toBeArray()
        ->and($result)->toHaveKeys(['order', 'stripe_url'])
        ->and($result['stripe_url'])->toBe('https://checkout.stripe.com/pay/cs_test_abc123')
        ->and($result['order']->stripe_session_id)->toBe('cs_test_abc123')
        ->and($result['order']->status)->toBe(OrderStatus::PENDING)
        ->and($result['order']->checked_out_at)->not->toBeNull();
});

test('verify payment action marks order as paid when stripe confirms payment', function () {
    Event::fake();

    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
        'stripe_session_id' => 'cs_test_paid123',
        'checked_out_at' => now(),
    ]);

    $fakeSession = makeFakeStripeSession('cs_test_paid123', 'paid', 'pi_test_intent123');

    $mockStripeService = Mockery::mock(StripeService::class);
    $mockStripeService->shouldReceive('retrieveCheckoutSession')
        ->once()
        ->with('cs_test_paid123')
        ->andReturn($fakeSession);
    $mockStripeService->shouldReceive('isSessionPaid')
        ->once()
        ->with($fakeSession)
        ->andReturnTrue();

    $this->app->instance(StripeService::class, $mockStripeService);

    $action = app(VerifyPaymentAction::class);
    $result = $action->execute('cs_test_paid123');

    expect($result)->not->toBeNull()
        ->and($result->status)->toBe(OrderStatus::PAID)
        ->and($result->stripe_payment_intent_id)->toBe('pi_test_intent123');

    Event::assertDispatched(OrderPaid::class, function (OrderPaid $event) use ($order) {
        return $event->order->id === $order->id;
    });
});

test('verify payment action returns null for unknown session id', function () {
    $mockStripeService = Mockery::mock(StripeService::class);
    $this->app->instance(StripeService::class, $mockStripeService);

    $action = app(VerifyPaymentAction::class);
    $result = $action->execute('cs_test_nonexistent');

    expect($result)->toBeNull();
});

test('verify payment action is idempotent for already paid orders', function () {
    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'paid',
        'stripe_session_id' => 'cs_test_already_paid',
        'stripe_payment_intent_id' => 'pi_test_existing',
        'checked_out_at' => now(),
    ]);

    $mockStripeService = Mockery::mock(StripeService::class);
    $mockStripeService->shouldNotReceive('retrieveCheckoutSession');

    $this->app->instance(StripeService::class, $mockStripeService);

    $action = app(VerifyPaymentAction::class);
    $result = $action->execute('cs_test_already_paid');

    expect($result)->not->toBeNull()
        ->and($result->id)->toBe($order->id)
        ->and($result->status)->toBe(OrderStatus::PAID);
});

test('verify payment action returns null when stripe reports unpaid', function () {
    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
        'stripe_session_id' => 'cs_test_unpaid',
        'checked_out_at' => now(),
    ]);

    $fakeSession = makeFakeStripeSession('cs_test_unpaid', 'unpaid');

    $mockStripeService = Mockery::mock(StripeService::class);
    $mockStripeService->shouldReceive('retrieveCheckoutSession')
        ->once()
        ->andReturn($fakeSession);
    $mockStripeService->shouldReceive('isSessionPaid')
        ->once()
        ->andReturnFalse();

    $this->app->instance(StripeService::class, $mockStripeService);

    $action = app(VerifyPaymentAction::class);
    $result = $action->execute('cs_test_unpaid');

    expect($result)->toBeNull();

    $order->refresh();
    expect($order->status)->toBe(OrderStatus::PENDING);
});

test('verify payment action returns null for cancelled orders', function () {
    Order::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'cancelled',
        'stripe_session_id' => 'cs_test_cancelled',
        'checked_out_at' => now(),
    ]);

    $mockStripeService = Mockery::mock(StripeService::class);
    $this->app->instance(StripeService::class, $mockStripeService);

    $action = app(VerifyPaymentAction::class);
    $result = $action->execute('cs_test_cancelled');

    expect($result)->toBeNull();
});

test('success page requires session_id parameter', function () {
    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'paid',
        'stripe_session_id' => 'cs_test_success',
        'stripe_payment_intent_id' => 'pi_test_success',
        'checked_out_at' => now(),
    ]);

    $this->actingAs($this->user);

    $this->get(route('checkout.success', ['order' => $order->id]))
        ->assertStatus(404);
});

test('success page rejects mismatched session_id', function () {
    $order = Order::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
        'stripe_session_id' => 'cs_test_real_session',
        'checked_out_at' => now(),
    ]);

    $this->actingAs($this->user);

    $this->get(route('checkout.success', ['order' => $order->id, 'session_id' => 'cs_test_fake_session']))
        ->assertStatus(403);
});

test('stripe service correctly identifies paid sessions', function () {
    $session = makeFakeStripeSession('cs_test_check_paid', 'paid');

    $mockService = Mockery::mock(StripeService::class)->makePartial();

    expect($mockService->isSessionPaid($session))->toBeTrue();
});

test('stripe service correctly identifies unpaid sessions', function () {
    $session = makeFakeStripeSession('cs_test_check_unpaid', 'unpaid');

    $mockService = Mockery::mock(StripeService::class)->makePartial();

    expect($mockService->isSessionPaid($session))->toBeFalse();
});
