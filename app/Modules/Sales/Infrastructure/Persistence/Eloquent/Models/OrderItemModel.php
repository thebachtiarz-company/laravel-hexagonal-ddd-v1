<?php

declare(strict_types=1);

namespace App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models;

use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Factories\OrderItemFactory;
use App\Shared\Infrastructure\Persistence\Eloquent\HasFactory;
use App\Shared\Infrastructure\Persistence\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemModel extends Model
{
    use HasFactory;

    public const string TABLE = 'order_items';

    public const string ORDER_ID = 'order_id';

    public const string SKU = 'sku';

    public const string QTY = 'qty';

    public const string SNAPSHOT = 'snapshot';

    public const string PRICE = 'price';

    /**
     * Create a new Eloquent model instance.
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(self::TABLE);

        $this->fillable([
            self::ORDER_ID,
            self::SKU,
            self::QTY,
            self::SNAPSHOT,
            self::PRICE,
        ]);

        $this->modelFactory = OrderItemFactory::class;

        parent::__construct($attributes);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderModel::class, self::ORDER_ID, new OrderModel()->getKeyName());
    }

    public function getOrderId(): ?int
    {
        return $this->getData(self::ORDER_ID);
    }

    public function getSku(): ?string
    {
        return $this->getData(self::SKU);
    }

    public function getQty(): ?int
    {
        return $this->getData(self::QTY);
    }

    public function getSnapshot(): ?array
    {
        return $this->getData(self::SNAPSHOT);
    }

    public function getPrice(): ?float
    {
        return floatval($this->getData(self::PRICE));
    }

    public function setOrderId(int $orderId): static
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    public function setSku(string $sku): static
    {
        return $this->setData(self::SKU, $sku);
    }

    public function setQty(int $qty): static
    {
        return $this->setData(self::QTY, $qty);
    }

    public function setSnapshot(array $snapshot): static
    {
        return $this->setData(self::SNAPSHOT, $snapshot);
    }

    public function setPrice(float $price): static
    {
        return $this->setData(self::PRICE, $price);
    }

    protected function casts()
    {
        return [
            self::SNAPSHOT => 'array',
        ];
    }
}
