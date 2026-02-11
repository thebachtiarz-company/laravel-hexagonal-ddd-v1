<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Eloquent;

/**
 * @var \Illuminate\Database\Eloquent\Model $this
 *
 * @template TFactory of \Illuminate\Database\Eloquent\Factories\Factory
 */
trait HasFactory
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * Define model factory
     *
     * @var class-string<TFactory>|null
     */
    protected ?string $modelFactory = null;

    /**
     * Create a new factory instance for the model.
     *
     * @return TFactory|null
     */
    protected static function newFactory()
    {
        return new self()->modelFactory ? new self()->modelFactory::new() : null;
    }
}
