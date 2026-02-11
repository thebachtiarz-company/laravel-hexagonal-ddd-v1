<?php

declare(strict_types=1);

namespace App\Modules\Sales\Infrastructure\ExternalServices;

use App\Modules\Catalog\Application\DTO\ProductDetailDTO;

class SalesProductService
{
    public function __construct() {}

    public function findProductBySku(string $sku): ?ProductDetailDTO
    {
        return new ProductDetailDTO(
            id: mt_rand(1, 90),
            sku: $sku,
            name: fake()->words(asText: true),
            price: round(mt_rand(10000, 999999), 2),
        );
    }
}
