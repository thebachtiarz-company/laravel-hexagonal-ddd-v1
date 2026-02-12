<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Application\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: \App\Modules\Catalog\Domain\Ports\ProductRepositoryInterface::class,
            concrete: \App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Repositories\ProductRepository::class,
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
