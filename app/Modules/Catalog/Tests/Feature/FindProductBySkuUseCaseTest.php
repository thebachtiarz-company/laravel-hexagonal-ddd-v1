<?php

declare(strict_types=1);

use App\Modules\Catalog\Application\UseCases\Product\FindProductBySkuUseCase;
use App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Models\ProductModel;

use function Pest\Laravel\assertDatabaseHas;

it('can find product by sku', function (): void {
    $product = ProductModel::factory()->create();

    assert($product instanceof ProductModel);

    assertDatabaseHas(ProductModel::TABLE, [
        ProductModel::SKU => $product->getSku(),
    ]);

    $process = app(FindProductBySkuUseCase::class)($product->getSku());

    expect($process->sku)->toBe($product->getSku());
    expect($process->name)->toBe($product->getName());
    expect($process->price)->toBe($product->getPrice());
});
