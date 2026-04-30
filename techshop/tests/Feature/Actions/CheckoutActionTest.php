<?php

use App\Actions\Checkout\CheckoutAction;
use App\Enums\OrderStatus;
use App\Livewire\Forms\CheckoutForm;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Support\Facades\Auth;

test('CheckoutAction creates a Stripe session and updates the order', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::login($user);

    $product = Product::factory()->create(['price' => 100]);

    // Create a pending order for the user (as the action expects it to exist for auth users)
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'checked_out_at' => null,
    ]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => 100,
    ]);

    $cartItems = collect([$orderItem]);

    // Mock CheckoutForm
    $form = Mockery::mock(CheckoutForm::class);
    $form->email = 'test@example.com';
    $form->firstName = 'John';
    $form->lastName = 'Doe';
    $form->addressLine1 = 'Street 1';
    $form->addressLine2 = null;
    $form->postcode = '1234';
    $form->city = 'City';
    $form->country = 'BE';
    $form->phone = null;

    // Mock StripeService
    $this->mock(StripeService::class, function ($mock) {
        $mock->shouldReceive('createCheckoutSession')
            ->once()
            ->andReturn((object) [
                'id' => 'sess_123',
                'url' => 'https://checkout.stripe.com/test',
            ]);
    });

    $action = app(CheckoutAction::class);

    // Act
    $result = $action->execute($form, $cartItems);

    // Assert
    expect($result)->toBeArray()
        ->and($result['stripe_url'])->toBe('https://checkout.stripe.com/test')
        ->and($result['order']->stripe_session_id)->toBe('sess_123')
        ->and($result['order']->status)->toBe(OrderStatus::PENDING);

    expect($result['order']->checked_out_at)->not->toBeNull();
});
