<?php

declare(strict_types=1);

namespace App\Modules\Sales\Domain\Ports;

use App\Modules\Sales\Domain\Entities\OrderEntity;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?OrderEntity;

    public function create(OrderEntity $order): OrderEntity;
}
