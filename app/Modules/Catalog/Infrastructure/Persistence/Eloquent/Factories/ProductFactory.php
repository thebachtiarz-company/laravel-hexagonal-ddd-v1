<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Factories;

use App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Models\ProductModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = ProductModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(asText: true);

        return [
            ProductModel::SKU => Str::upper(Str::snake($name, '-')),
            ProductModel::NAME => $name,
            ProductModel::PRICE => round(mt_rand(10000, 999999), 2),
        ];
    }
}
