<?php

declare(strict_types=1);

use App\Modules\Catalog\Application\DTO\ProductCreateDTO;
use App\Modules\Catalog\Application\UseCases\Product\CreateNewProductUseCase;
use App\Modules\Catalog\Domain\Entities\ProductEntity;
use App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Models\ProductModel;
use Illuminate\Support\Str;

use function Pest\Laravel\assertDatabaseHas;

it('can crete new product', function (): void {
    $entity = new ProductEntity;

    $entity->name = fake()->words(asText: true);
    $entity->sku = Str::lower(Str::snake($entity->name, '-'));
    $entity->price = round(mt_rand(10000, 999999), 2);

    $dto = new ProductCreateDTO(
        sku: $entity->sku,
        name: $entity->name,
        price: $entity->price,
    );

    app(CreateNewProductUseCase::class)($dto);

    assertDatabaseHas(ProductModel::TABLE, [
        ProductModel::SKU => $entity->sku,
        ProductModel::NAME => $entity->name,
        ProductModel::PRICE => $entity->price,
    ]);
});
