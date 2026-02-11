<?php

declare(strict_types=1);

namespace App\Modules\Sales\Application\DTO;

class OrderItemDTO
{
    public function __construct(
        public string $sku,
        public ?int $qty = 1,
    ) {}
}
