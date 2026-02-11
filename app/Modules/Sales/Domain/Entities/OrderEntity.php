<?php

declare(strict_types=1);

namespace App\Modules\Sales\Domain\Entities;

use App\Modules\Sales\Domain\Enums\OrderStatusEnum;

class OrderEntity
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?string $code = null,
        public ?string $status = null,
    ) {}

    public function getStatusLabel(): ?string
    {
        return OrderStatusEnum::tryFrom($this->status)->getLabel();
    }
}
