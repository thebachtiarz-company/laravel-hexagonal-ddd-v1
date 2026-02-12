<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Models;

use App\Modules\Catalog\Infrastructure\Persistence\Eloquent\Factories\ProductFactory;
use App\Shared\Infrastructure\Persistence\Eloquent\HasFactory;
use App\Shared\Infrastructure\Persistence\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    public const string TABLE = 'products';

    public const string SKU = 'sku';

    public const string NAME = 'name';

    public const string PRICE = 'price';

    /**
     * Create a new Eloquent model instance.
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(self::TABLE);

        $this->fillable([
            self::SKU,
            self::NAME,
            self::PRICE,
        ]);

        $this->modelFactory = ProductFactory::class;

        parent::__construct($attributes);
    }

    public function getSku(): ?string
    {
        return $this->getData(self::SKU);
    }

    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    public function getPrice(): ?float
    {
        return floatval($this->getData(self::PRICE));
    }

    public function setSku(string $sku): static
    {
        return $this->setData(self::SKU, $sku);
    }

    public function setName(string $name): static
    {
        return $this->setData(self::NAME, $name);
    }

    public function setPrice(float $price): static
    {
        return $this->setData(self::PRICE, $price);
    }
}
