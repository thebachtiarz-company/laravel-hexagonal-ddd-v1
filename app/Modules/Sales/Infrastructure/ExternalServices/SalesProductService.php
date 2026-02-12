<?php

declare(strict_types=1);

namespace App\Modules\Sales\Infrastructure\ExternalServices;

use App\Modules\Catalog\Application\UseCases\Product\FindProductBySkuUseCase;
use App\Modules\Sales\Domain\ValueObjects\CatalogProductDetailData;

class SalesProductService
{
    public function __construct(
        protected FindProductBySkuUseCase $findProductBySku,
    ) {}

    public function findProductBySku(string $sku): ?CatalogProductDetailData
    {
        $product = $this->findProductBySku($sku);

        if (! $product) {
            return null;
        }

        return new CatalogProductDetailData(
            id: $product->id,
            sku: $product->sku,
            name: $product->name,
            price: $product->price,
        );
    }
}
