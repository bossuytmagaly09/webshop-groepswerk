<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

use App\Http\Controllers\SocialLoginController;
use App\Livewire\Public\CartOverview;

Route::get('/cart', CartOverview::class)->name('cart.index');

Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback');

require __DIR__.'/settings.php';
