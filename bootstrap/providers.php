<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Modules\Catalog\Application\Providers\ServiceProvider::class,
    App\Modules\Sales\Application\Providers\ServiceProvider::class,
];
