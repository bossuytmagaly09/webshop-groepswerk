<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/products', 'pages::product-catalog')->name('products');

Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/shipping-returns', 'pages.shipping-returns')->name('shipping-returns');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('mijn-orders', \App\Livewire\Public\MyOrders::class)->name('my-orders');
});

use App\Http\Controllers\SocialLoginController;

Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback');

require __DIR__.'/settings.php';
