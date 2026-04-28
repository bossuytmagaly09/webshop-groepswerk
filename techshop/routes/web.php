<?php

use App\Http\Controllers\SocialLoginController;
use App\Livewire\Public\CartOverview;
use App\Livewire\Public\Checkout;
use App\Livewire\Public\CheckoutSuccess;
use App\Livewire\Public\MyOrders;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/products', 'pages::product-catalog')->name('products');
Route::livewire('/products/{product:slug}', 'pages::product-detail')->name('products.show');

Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/shipping-returns', 'pages.shipping-returns')->name('shipping-returns');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('dashboard/categories', 'pages::dashboard.category-manager')->name('dashboard.categories');
    Route::livewire('dashboard/products', 'pages::dashboard.product-manager')->name('dashboard.products');
    Route::get('mijn-orders', MyOrders::class)->name('my-orders');
    Route::livewire('qr-login/{token}', 'auth.qr-login-confirm')->name('qr.login');
});

Route::get('/cart', CartOverview::class)->name('cart.index');
Route::get('/checkout', Checkout::class)->name('checkout.index');
Route::get('/checkout/success/{order}', CheckoutSuccess::class)->name('checkout.success');

Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback');

require __DIR__.'/settings.php';
