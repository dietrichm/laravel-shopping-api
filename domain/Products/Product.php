<?php

namespace Domain\Products;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @method static Product id(ProductId $productId)
 */
final class Product extends Model
{
    public function getId(): ProductId
    {
        return ProductId::fromString($this->uuid);
    }

    public function setId(ProductId $productId): void
    {
        $this->uuid = $productId->toString();
    }

    public function getName(): ProductName
    {
        return ProductName::fromString($this->name);
    }

    public function setName(ProductName $productName): void
    {
        $this->name = $productName->toString();
    }

    public function getPrice(): Money
    {
        return Money::fromValue($this->price);
    }

    public function setPrice(Money $price): void
    {
        $this->price = $price->getValue();
    }

    public function scopeId(Builder $query, ProductId $productId): self
    {
        try {
            $product = $query
                ->where('uuid', $productId->toString())
                ->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw ProductDoesNotExist::withId($productId);
        }

        return $product;
    }
}
