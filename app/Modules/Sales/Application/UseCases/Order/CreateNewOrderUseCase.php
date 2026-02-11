<?php

declare(strict_types=1);

namespace App\Modules\Sales\Application\UseCases\Order;

use App\Modules\Sales\Application\DTO\OrderCreateDTO;
use App\Modules\Sales\Domain\Entities\OrderEntity;
use App\Modules\Sales\Domain\Entities\OrderItemEntity;
use App\Modules\Sales\Domain\Ports\OrderItemRepositoryInterface;
use App\Modules\Sales\Domain\Ports\OrderRepositoryInterface;
use App\Modules\Sales\Infrastructure\ExternalServices\SalesProductService;
use Illuminate\Support\Facades\DB;

class CreateNewOrderUseCase
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderItemRepositoryInterface $orderItemRepository,
        protected SalesProductService $salesProductService,
    ) {}

    public function __invoke(OrderCreateDTO $data): void
    {
        DB::transaction(function () use ($data): void {
            $order = $this->orderRepository->create(new OrderEntity(
                userId: $data->userId,
                code: $data->code,
            ));

            foreach ($data->items as $key => $item) {
                $product = $this->salesProductService->findProductBySku($item->sku);

                if (! $product) {
                    continue;
                }

                $this->orderItemRepository->create(new OrderItemEntity(
                    orderId: $order->id,
                    sku: $item->sku,
                    qty: $item->qty,
                    snapshot: $product->toArray(),
                    price: $product->price,
                ));
            }
        });
    }
}
