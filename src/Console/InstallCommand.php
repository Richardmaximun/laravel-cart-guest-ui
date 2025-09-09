<?php

namespace Richardmaximun\CartGuestUi\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'cart-guest-ui:install
                            {--force : Sobrescribe archivos ya publicados}
                            {--no-interaction : No pedir confirmaciones}
                            {--migrate : Ejecutar php artisan migrate al finalizar}';

    protected $description = 'Publica recursos de binafy/laravel-cart y de este paquete, opcionalmente migra.';

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        // 1) Publicar binafy/laravel-cart
        $this->info('Publicando recursos de binafy/laravel-cart...');
        $this->call('vendor:publish', [
            '--provider' => 'Binafy\\LaravelCart\\Providers\\LaravelCartServiceProvider',
            '--force'    => $force,
        ]);

        // 2) Publicar config + vistas de este paquete
        $this->info('Publicando config y vistas de richardmaximun/laravel-cart-guest-ui...');
        $this->call('vendor:publish', [
            '--tag'   => 'cart-guest-ui-config',
            '--force' => $force,
        ]);
        $this->call('vendor:publish', [
            '--tag'   => 'cart-guest-ui-views',
            '--force' => $force,
        ]);

        // 3) ¿Migrar?
        if ($this->option('migrate')) {
            $this->info('Ejecutando migraciones...');
            $this->call('migrate');
        } else {
            $this->line('> Tip: corre "php artisan migrate" para aplicar las migraciones de laravel-cart.');
        }

        $this->info('Instalación completada ✅');
        return self::SUCCESS;
    }
}