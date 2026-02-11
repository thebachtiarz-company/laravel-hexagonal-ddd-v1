<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Attributes\Scope;

/**
 * @var \Illuminate\Database\Eloquent\Model $this
 */
trait HasModel
{
    public function getData(string $key, mixed $default = null): mixed
    {
        return $this->__get(key: $key) ?? $default;
    }

    public function setData(string $key, mixed $value = null, bool $forceSet = true): static
    {
        if ($forceSet) {
            $this->__set(key: $key, value: $value);
        } else {
            $this->{$key} ??= $value;
        }

        return $this;
    }

    public function getId(): int|string|null
    {
        return $this->getData(key: $this->getKeyName());
    }

    public function setId(int $id): static
    {
        return $this->setData(key: $this->getKeyName(), value: $id);
    }

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->getData(key: \Illuminate\Database\Eloquent\Model::CREATED_AT);
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->getData(key: \Illuminate\Database\Eloquent\Model::UPDATED_AT);
    }

    public function modelMap(array $attributes = [], array $hides = []): array
    {
        $this->setVisible(
            visible: array_merge(static::getTableColumns(), $attributes),
        );

        $this->makeHidden(
            attributes: array_unique(
                array: array_merge(
                    [
                        $this->getKeyName(),
                        \Illuminate\Database\Eloquent\Model::CREATED_AT,
                        \Illuminate\Database\Eloquent\Model::UPDATED_AT,
                    ],
                    $hides,
                ),
            ),
        );

        return $this->toArray();
    }

    public static function getTableColumns(): array
    {
        return \Illuminate\Support\Facades\Schema::getColumnListing(static::{'TABLE'});
    }

    #[Scope]
    protected function whereAttribute(
        \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $builder,
        string $column,
        mixed $value,
        string $operator = '=',
    ): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder {
        return $builder->where(
            column: \Illuminate\Support\Facades\DB::raw("BINARY `$column`"),
            operator: $operator,
            value: $value,
        );
    }
}
