<?php

return [
    'badge' => [
        'mode' => env('CART_BADGE_MODE', 'livewire'), // 'livewire' | 'ajax'
        'poll' => env('CART_BADGE_POLL', 10),         // segundos
        'route' => 'cart.badge',
    ],
    'mini_cart' => [
        'enabled' => env('CART_MINI_CART', true),
        'limit' => env('CART_MINI_CART_LIMIT', 5),
    ],
    'media' => [
        'conversion' => env('CART_MEDIA_CONVERSION', 'thumb'),
        'placeholder' => env('CART_PLACEHOLDER', '/images/placeholder.png'),
    ],
    'routes' => [
        'prefix' => '',
        'middleware' => ['web'],
        'names' => [
            'index'   => 'cart.index',
            'store'   => 'cart.store',
            'update'  => 'cart.update',
            'destroy' => 'cart.destroy',
        ],
    ],
];