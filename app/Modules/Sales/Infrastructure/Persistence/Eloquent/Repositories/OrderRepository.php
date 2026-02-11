<?php

declare(strict_types=1);

namespace App\Modules\Sales\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\Sales\Domain\Entities\OrderEntity;
use App\Modules\Sales\Domain\Enums\OrderStatusEnum;
use App\Modules\Sales\Domain\Ports\OrderRepositoryInterface;
use App\Modules\Sales\Infrastructure\Helper\OrderHelper;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderModel;

class OrderRepository implements OrderRepositoryInterface
{
    public function findById(int $id): ?OrderEntity
    {
        $model = OrderModel::find($id);

        if (! $model) {
            return null;
        }

        assert($model instanceof OrderModel);

        return new OrderEntity(
            id: $model->getId(),
            userId: $model->getUserId(),
            code: $model->getCode(),
        );
    }

    public function create(OrderEntity $order): OrderEntity
    {
        $model = new OrderModel;

        $model->setUserId($order->userId);
        $model->setCode($order->code ?? OrderHelper::generateCode());
        $model->setStatus($order->status ?? OrderStatusEnum::CREATED->value);

        $model->save();

        $model = $model->fresh();

        return new OrderEntity(
            id: $model->getId(),
            userId: $model->getUserId(),
            code: $model->getCode(),
            status: $model->getStatus(),
        );
    }
}
