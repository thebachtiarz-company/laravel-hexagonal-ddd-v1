<?php

declare(strict_types=1);

namespace App\Modules\Sales\Domain\Enums;

enum OrderStatusEnum: string
{
    case CREATED = 'created';
    case PAID = 'paid';
    case FRAUD = 'fraud';
    case PACKED = 'packed';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case FINISHED = 'finished';
    case CANCELED = 'canceled';

    /**
     * Get label
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::CREATED => 'Created',
            self::PAID => 'Paid',
            self::FRAUD => 'Fraud',
            self::PACKED => 'Packed',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::FINISHED => 'Finished',
            self::CANCELED => 'Canceled',
        };
    }
}
