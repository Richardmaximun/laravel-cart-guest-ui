<?php

namespace Richardmaximun\CartGuestUi\Livewire\Cart;

use Livewire\Component;
use Richardmaximun\CartGuestUi\Support\CartGateway;

class Badge extends Component
{
    public int $poll = 10;

    public function mount() { $this->poll = (int) config('cart-guest-ui.badge.poll', 10); }

    public function render(CartGateway $cart)
    {
        return view('cart-guest-ui::components.cart.badge-livewire', [
            'count'    => $cart->count(),
            'items'    => $cart->items((int) config('cart-guest-ui.mini_cart.limit', 5)),
            'miniCart' => (bool) config('cart-guest-ui.mini_cart.enabled', true),
        ]);
    }
}