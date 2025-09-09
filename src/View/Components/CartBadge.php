<?php

namespace Richardmaximun\CartGuestUi\View\Components;

use Illuminate\View\Component;

class CartBadge extends Component
{
    public function render()
    {
        return match (config('cart-guest-ui.badge.mode', 'livewire')) {
            'ajax'   => view('cart-guest-ui::components.cart.badge-ajax'),
            default  => view('cart-guest-ui::components.cart.badge-livewire'),
        };
    }
}