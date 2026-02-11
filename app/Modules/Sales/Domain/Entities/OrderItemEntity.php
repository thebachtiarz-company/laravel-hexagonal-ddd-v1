<?php

declare(strict_types=1);

namespace App\Modules\Sales\Domain\Entities;

class OrderItemEntity
{
    public function __construct(
        public ?int $id = null,
        public ?int $orderId = null,
        public ?string $sku = null,
        public ?int $qty = null,
        public ?array $snapshot = null,
        public ?float $price = null,
    ) {}
}
