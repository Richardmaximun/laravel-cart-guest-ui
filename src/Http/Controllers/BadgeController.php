<?php

namespace Richardmaximun\CartGuestUi\Http\Controllers;

use Illuminate\Http\Request;
use Richardmaximun\CartGuestUi\Support\CartGateway;

class BadgeController
{
    public function __construct(protected CartGateway $cart) {}

    public function show(Request $request)
    {
        $limit = (int) config('cart-guest-ui.mini_cart.limit', 5);
        return response()->json([
            'count' => $this->cart->count(),
            'items' => $this->cart->items($limit),
        ]);
    }
}