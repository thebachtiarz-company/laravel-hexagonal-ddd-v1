<?php

declare(strict_types=1);

namespace App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models;

use App\Models\User;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Factories\OrderFactory;
use App\Shared\Infrastructure\Persistence\Eloquent\HasFactory;
use App\Shared\Infrastructure\Persistence\Eloquent\HasSoftDelete;
use App\Shared\Infrastructure\Persistence\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderModel extends Model
{
    use HasFactory;
    use HasSoftDelete;

    public const string TABLE = 'orders';

    public const string USER_ID = 'user_id';

    public const string CODE = 'code';

    public const string STATUS = 'status';

    /**
     * Create a new Eloquent model instance.
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(self::TABLE);

        $this->fillable([
            self::USER_ID,
            self::CODE,
            self::STATUS,
        ]);

        $this->modelFactory = OrderFactory::class;

        parent::__construct($attributes);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, self::USER_ID, new User()->getKeyName());
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, OrderItemModel::ORDER_ID, $this->getKeyName());
    }

    public function getUserId(): ?int
    {
        return $this->getData(self::USER_ID);
    }

    public function getCode(): ?string
    {
        return $this->getData(self::CODE);
    }

    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS);
    }

    public function setUserId(int $userId): static
    {
        return $this->setData(self::USER_ID, $userId);
    }

    public function setCode(string $code): static
    {
        return $this->setData(self::CODE, $code);
    }

    public function setStatus(string $status): static
    {
        return $this->setData(self::STATUS, $status);
    }
}
