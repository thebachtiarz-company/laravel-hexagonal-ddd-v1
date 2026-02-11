<?php

declare(strict_types=1);

use App\Modules\Sales\Application\DTO\OrderCreateDTO;
use App\Modules\Sales\Application\DTO\OrderItemDTO;
use App\Modules\Sales\Application\UseCases\Order\CreateNewOrderUseCase;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderItemModel;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderModel;
use Illuminate\Support\Str;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('can crete new order', function (): void {
    $order = OrderModel::factory()->make();
    assert($order instanceof OrderModel);

    $itemShouldCreateCount = mt_rand(2, 7);

    $dto = new OrderCreateDTO(
        userId: $order->getUserId(),
        code: $order->getCode(),
        items: (function () use ($itemShouldCreateCount): array {
            $items = [];

            for ($i = 0; $i < $itemShouldCreateCount; $i++) {
                $items[] = new OrderItemDTO(
                    sku: Str::random(),
                    qty: mt_rand(1, 5),
                );
            }

            return $items;
        })(),
    );

    app(CreateNewOrderUseCase::class)($dto);

    assertDatabaseHas(OrderModel::TABLE, [
        OrderModel::USER_ID => $order->getUserId(),
        OrderModel::CODE => $order->getCode(),
    ]);

    assertDatabaseCount(OrderItemModel::TABLE, $itemShouldCreateCount);
});
