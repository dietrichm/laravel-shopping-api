<?php

namespace Domain\Orders;

use Spatie\EventSourcing\AggregateRoot;

final class Order extends AggregateRoot
{
    /**
     * @var OrderId
     */
    private $orderId;

    /**
     * @var bool
     */
    private $new = true;

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

    public function isNew(): bool
    {
        return $this->new;
    }

    protected function applyOrderWasCreated(): void
    {
        $this->new = false;
    }
}
