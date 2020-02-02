<?php

namespace Domain\Orders;

final class Order
{
    /**
     * @var OrderId
     */
    private $orderId;

    public function __construct(OrderId $orderId)
    {
        $this->orderId = $orderId;
    }

    public function getId(): OrderId
    {
        return $this->orderId;
    }
}
