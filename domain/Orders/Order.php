<?php

namespace Domain\Orders;

use Spatie\EventSourcing\AggregateRoot;

final class Order extends AggregateRoot
{
    /**
     * @var OrderId
     */
    private $orderId;

    public static function findOrCreate(OrderId $orderId): self
    {
        /** @var Order $order */
        $order = parent::retrieve($orderId->toString());

        $order->orderId = $orderId;

        return $order;
    }

    public function getId(): OrderId
    {
        return $this->orderId;
    }
}
