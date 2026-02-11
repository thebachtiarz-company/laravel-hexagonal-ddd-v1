<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Eloquent;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasModel;
}
