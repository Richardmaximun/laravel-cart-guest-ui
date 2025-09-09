<?php

namespace Richardmaximun\CartGuestUi\Listeners;

use Illuminate\Auth\Events\Login;
use Richardmaximun\CartGuestUi\Support\CartGateway;

class MergeGuestCartOnLogin
{
    public function __construct(protected CartGateway $cart) {}

    public function handle(Login $event): void
    {
        $this->cart->mergeIntoUser($event->user->getKey());
    }
}