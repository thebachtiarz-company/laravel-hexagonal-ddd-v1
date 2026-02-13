<?php

declare(strict_types=1);

use App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Models\ProductModel;
use App\Modules\Sales\Application\DTO\OrderCreateDTO;
use App\Modules\Sales\Application\DTO\OrderItemDTO;
use App\Modules\Sales\Application\UseCases\Order\CreateNewOrderUseCase;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderItemModel;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderModel;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('can crete new order', function (): void {
    $order = OrderModel::factory()->make();
    assert($order instanceof OrderModel);

    $itemShouldCreateCount = mt_rand(2, 7);

    $items = ProductModel::factory($itemShouldCreateCount)->create()
        ->map(fn (ProductModel $item): OrderItemDTO => new OrderItemDTO(sku: $item->getSku(), qty: mt_rand(1, 5)))
        ->toArray();

    $dto = new OrderCreateDTO(
        userId: $order->getUserId(),
        code: $order->getCode(),
        items: $items,
    );

    app(CreateNewOrderUseCase::class)($dto);

    assertDatabaseHas(OrderModel::TABLE, [
        OrderModel::USER_ID => $order->getUserId(),
        OrderModel::CODE => $order->getCode(),
    ]);

    assertDatabaseCount(OrderItemModel::TABLE, $itemShouldCreateCount);
});
