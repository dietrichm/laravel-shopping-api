<?php

namespace Domain\Orders;

use RuntimeException;

final class OrderIsAlreadyCheckedOut extends RuntimeException
{
    public static function withId(OrderId $orderId): self
    {
        return new self(
            'Order ' . $orderId->toString() . ' is already checked out'
        );
    }
}
