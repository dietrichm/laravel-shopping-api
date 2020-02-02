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

    /**
     * @var LineItem[]
     */
    private $lineItems = [];

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

    /**
     * @return LineItem[]
     */
    public function getLineItems(): array
    {
        return $this->lineItems;
    }

    protected function applyOrderWasCreated(): void
    {
        $this->new = false;
    }

    protected function applyLineItemWasAddedToOrder(
        LineItemWasAddedToOrder $event
    ): void {
        $lineItem = new LineItem(
            $event->getLineItemId(),
            $event->getProductId()
        );

        $this->lineItems[] = $lineItem;
    }
}
