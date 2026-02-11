<?php

declare(strict_types=1);

namespace App\Modules\Sales\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\Sales\Domain\Entities\OrderItemEntity;
use App\Modules\Sales\Domain\Ports\OrderItemRepositoryInterface;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderItemModel;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    public function findById(int $id): ?OrderItemEntity
    {
        $model = OrderItemModel::find($id);

        if (! $model) {
            return null;
        }

        assert($model instanceof OrderItemModel);

        return new OrderItemEntity(
            id: $model->getId(),
            orderId: $model->getOrderId(),
            sku: $model->getSku(),
            qty: $model->getQty(),
            snapshot: $model->getSnapshot(),
            price: $model->getPrice(),
        );
    }

    public function create(OrderItemEntity $item): OrderItemEntity
    {
        $model = new OrderItemModel;

        $model->setOrderId($item->orderId);
        $model->setSku($item->sku);
        $model->setQty($item->qty);
        $model->setSnapshot($item->snapshot);
        $model->setPrice($item->price);

        $model->save();

        $model = $model->fresh();

        return new OrderItemEntity(
            id: $model->getId(),
            orderId: $model->getOrderId(),
            sku: $model->getSku(),
            qty: $model->getQty(),
            snapshot: $model->getSnapshot(),
            price: $model->getPrice(),
        );
    }
}
