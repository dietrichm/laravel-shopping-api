<?php

namespace Domain\Orders;

final class CreateOrder
{
    /**
     * @var OrderId
     */
    private $orderId;

    public function __construct(OrderId $orderId)
    {
        $this->orderId = $orderId;
    }

    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }
}
