<?php

declare(strict_types=1);

namespace App\Modules\Sales\Infrastructure\Persistence\Eloquent\Factories;

use App\Models\User;
use App\Modules\Sales\Domain\Enums\OrderStatusEnum;
use App\Modules\Sales\Infrastructure\Helper\OrderHelper;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class OrderFactory extends Factory
{
    protected $model = OrderModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            OrderModel::USER_ID => User::inRandomOrder()->first()->id ?? User::factory(),
            OrderModel::CODE => OrderHelper::generateCode(),
            OrderModel::STATUS => Arr::random(OrderStatusEnum::cases())->value,
        ];
    }
}
