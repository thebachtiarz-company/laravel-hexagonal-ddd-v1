<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\Catalog\Domain\Entities\ProductEntity;
use App\Modules\Catalog\Domain\Ports\ProductRepositoryInterface;
use App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Models\ProductModel;

class ProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?ProductEntity
    {
        $model = ProductModel::find($id);

        if (! $model) {
            return null;
        }

        assert($model instanceof ProductModel);

        return new ProductEntity(
            id: $model->getId(),
            sku: $model->getSku(),
            name: $model->getName(),
            price: $model->getPrice(),
        );
    }

    public function findBySku(string $sku): ?ProductEntity
    {
        $model = ProductModel::whereAttribute(column: ProductModel::SKU, value: $sku)->first();

        if (! $model) {
            return null;
        }

        return new ProductEntity(
            id: $model->getId(),
            sku: $model->getSku(),
            name: $model->getName(),
            price: $model->getPrice(),
        );
    }

    public function create(ProductEntity $product): ProductEntity
    {
        $model = new ProductModel;

        $model->setSku($product->sku);
        $model->setName($product->name);
        $model->setPrice($product->price);

        $model->save();

        $model = $model->fresh();

        return new ProductEntity(
            id: $model->getId(),
            sku: $model->getSku(),
            name: $model->getName(),
            price: $model->getPrice(),
        );
    }
}
