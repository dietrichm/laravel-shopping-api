<?php

namespace Domain\Orders;

use RuntimeException;

final class LineItemDoesNotExist extends RuntimeException
{
    public static function withId(LineItemId $lineItemId): self
    {
        return new self(
            'Line item ' . $lineItemId->toString() . ' does not exist'
        );
    }
}
