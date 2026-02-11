<?php

declare(strict_types=1);

namespace App\Modules\Sales\Infrastructure\Helper;

use Illuminate\Support\Str;

class OrderHelper
{
    public static function generateCode(): string
    {
        return sprintf(
            'ORD%s%s',
            today()->format('Ymd'),
            Str::upper(Str::random(7)),
        );
    }
}
