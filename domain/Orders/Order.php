<?php

namespace Domain\Orders;

use Illuminate\Support\Collection;
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
     * @var Collection
     */
    private $lineItems;

    public function __construct()
    {
        $this->lineItems = new Collection();
    }

    public static function findOrCreate(OrderId $orderId): self
    {
        /** @var Order $order */
        $order = parent::retrieve($orderId->toString());

        $order->orderId = $orderId;

        return $order;
    }

    public static function id(OrderId $orderId): self
    {
        $order = self::findOrCreate($orderId);

        if ($order->isNew()) {
            throw OrderDoesNotExist::withId($orderId);
        }

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
        return $this->lineItems->values()->all();
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

        $this->lineItems->offsetSet(
            $lineItem->getId()->toString(),
            $lineItem
        );
    }
}
