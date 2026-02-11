<?php

declare(strict_types=1);

namespace App\Modules\Sales\Application\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: \App\Modules\Sales\Domain\Ports\OrderRepositoryInterface::class,
            concrete: \App\Modules\Sales\Infrastructure\Persistence\Eloquent\Repositories\OrderRepository::class,
        );

        $this->app->bind(
            abstract: \App\Modules\Sales\Domain\Ports\OrderItemRepositoryInterface::class,
            concrete: \App\Modules\Sales\Infrastructure\Persistence\Eloquent\Repositories\OrderItemRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->mergeConfigFrom(__DIR__ . '/../../Infrastructure/Config/[config_name].php', '[config_name]');
        $this->loadMigrationsFrom(__DIR__ . '/../../Infrastructure/Persistence/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../UI/Routes/api.php');
    }
}
