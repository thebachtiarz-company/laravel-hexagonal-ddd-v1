<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Application\DTO;

class ProductDetailDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $sku = null,
        public ?string $name = null,
        public ?float $price = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
        ];
    }
}
