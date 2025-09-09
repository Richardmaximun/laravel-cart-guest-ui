<?php

namespace Richardmaximun\CartGuestUi;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Richardmaximun\CartGuestUi\Listeners\MergeGuestCartOnLogin;

class CartGuestUiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cart-guest-ui.php', 'cart-guest-ui');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/cart-guest-ui.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cart-guest-ui');

        $this->publishes([
            __DIR__ . '/../config/cart-guest-ui.php' => config_path('cart-guest-ui.php'),
        ], 'cart-guest-ui-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/cart-guest-ui'),
        ], 'cart-guest-ui-views');

        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component(
                'cart.badge',
                \Richardmaximun\CartGuestUi\Livewire\Cart\Badge::class
            );
        }

        // Registrar el comando solo en consola
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Richardmaximun\CartGuestUi\Console\InstallCommand::class,
            ]);
        }

        Event::listen(\Illuminate\Auth\Events\Login::class, MergeGuestCartOnLogin::class);
    }
}