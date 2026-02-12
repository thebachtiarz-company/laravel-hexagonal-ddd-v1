<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Application\DTO;

class ProductCreateDTO
{
    public function __construct(
        public string $sku,
        public string $name,
        public float $price,
    ) {}
}
