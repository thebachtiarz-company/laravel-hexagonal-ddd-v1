<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Eloquent;

/**
 * @var \Illuminate\Database\Eloquent\Model $this
 */
trait HasSoftDelete
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    public function getDeletedAt(): ?\Illuminate\Support\Carbon
    {
        return $this->__get($this->getDeletedAtColumn());
    }
}
