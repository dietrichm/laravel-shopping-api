<?php

namespace Domain\Products;

use RuntimeException;

final class ProductDoesNotExist extends RuntimeException
{
    public static function withId(ProductId $productId): self
    {
        return new self(
            'Product ' . $productId->toString() . ' does not exist'
        );
    }
}
