<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Application\UseCases\Product;

use App\Modules\Catalog\Application\DTO\ProductCreateDTO;
use App\Modules\Catalog\Domain\Entities\ProductEntity;
use App\Modules\Catalog\Domain\Ports\ProductRepositoryInterface;
use Illuminate\Support\Str;

class CreateNewProductUseCase
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
    ) {}

    public function __invoke(ProductCreateDTO $data): void
    {
        FIND:
        $product = $this->productRepository->findBySku($data->sku);

        if ($product) {
            $data->sku .= sprintf('-%s', Str::random(5));

            goto FIND;
        }

        $this->productRepository->create(new ProductEntity(
            sku: $data->sku,
            name: $data->name,
            price: $data->price,
        ));
    }
}
