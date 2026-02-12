<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Application\UseCases\Product;

use App\Modules\Catalog\Domain\Entities\ProductEntity;
use App\Modules\Catalog\Domain\Ports\ProductRepositoryInterface;

class FindProductBySkuUseCase
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
    ) {}

    public function __invoke(string $sku): ?ProductEntity
    {
        return $this->productRepository->findBySku($sku);
    }
}
