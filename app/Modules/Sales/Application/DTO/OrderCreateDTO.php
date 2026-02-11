<?php

declare(strict_types=1);

namespace App\Modules\Sales\Application\DTO;

class OrderCreateDTO
{
    /**
     * @param  array<int, OrderItemDTO>  $items
     */
    public function __construct(
        public int $userId,
        public array $items,
        public ?string $code,
        public ?string $status = null,
    ) {}
}
