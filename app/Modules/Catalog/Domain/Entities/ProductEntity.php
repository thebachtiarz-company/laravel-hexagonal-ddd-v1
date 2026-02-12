<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Domain\Entities;

class ProductEntity
{
    public function __construct(
        public ?int $id = null,
        public ?string $sku = null,
        public ?string $name = null,
        public ?float $price = null,
    ) {}
}
