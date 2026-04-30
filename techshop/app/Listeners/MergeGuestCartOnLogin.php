<?php

namespace App\Listeners;

use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class MergeGuestCartOnLogin
{
    public function __construct(protected CartService $cartService) {}

    /**
     * Voeg een eventuele sessie-cart van de gast samen met de DB-cart van de zojuist ingelogde gebruiker.
     */
    public function handle(Login $event): void
    {
        $this->cartService->mergeSessionCartIntoDatabase($event->user->getKey());
    }
}
