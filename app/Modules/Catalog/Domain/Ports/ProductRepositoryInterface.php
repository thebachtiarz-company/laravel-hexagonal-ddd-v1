<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Domain\Ports;

use App\Modules\Catalog\Domain\Entities\ProductEntity;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?ProductEntity;

    public function findBySku(string $sku): ?ProductEntity;

    public function create(ProductEntity $product): ProductEntity;
}
