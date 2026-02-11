<?php

declare(strict_types=1);

namespace App\Modules\Sales\Domain\Ports;

use App\Modules\Sales\Domain\Entities\OrderItemEntity;

interface OrderItemRepositoryInterface
{
    public function findById(int $id): ?OrderItemEntity;

    public function create(OrderItemEntity $item): OrderItemEntity;
}
