<?php

namespace Domain\Orders;

use RuntimeException;

final class OrderDoesNotExist extends RuntimeException
{
    public static function withId(OrderId $orderId): self
    {
        return new self(
            'Order ' . $orderId->toString() . ' does not exist'
        );
    }
}
