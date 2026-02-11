<?php

declare(strict_types=1);

namespace App\Modules\Sales\Infrastructure\Persistence\Eloquent\Factories;

use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderItemModel;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderItemFactory extends Factory
{
    protected $model = OrderItemModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            OrderItemModel::ORDER_ID => OrderModel::inRandomOrder()->first()?->getId() ?? OrderModel::factory(),
            OrderItemModel::SKU => Str::random(),
            OrderItemModel::QTY => mt_rand(1, 5),
            OrderItemModel::SNAPSHOT => [],
            OrderItemModel::PRICE => round(mt_rand(10000, 999999), 2),
        ];
    }
}
