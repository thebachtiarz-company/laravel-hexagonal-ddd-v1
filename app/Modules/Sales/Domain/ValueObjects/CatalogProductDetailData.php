<?php

declare(strict_types=1);

namespace App\Modules\Sales\Domain\ValueObjects;

class CatalogProductDetailData
{
    public function __construct(
        public readonly int $id,
        public readonly string $sku,
        public readonly string $name,
        public readonly float $price,
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
