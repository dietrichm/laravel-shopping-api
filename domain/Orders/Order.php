<?php

namespace Domain\Orders;

use Domain\Products\Product;
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
     * @var LineItemCollection
     */
    private $lineItems;

    /**
     * @var LineItemCollection
     */
    private $removedLineItems;

    public function __construct()
    {
        $this->lineItems = new LineItemCollection();
        $this->removedLineItems = new LineItemCollection();
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
        return $this->lineItems->all();
    }

    public function hasLineItem(LineItemId $lineItemId): bool
    {
        return $this->lineItems->has($lineItemId);
    }

    /**
     * @return LineItem[]
     */
    public function getRemovedLineItems(): array
    {
        return $this->removedLineItems->all();
    }

    protected function applyOrderWasCreated(): void
    {
        $this->new = false;
    }

    protected function applyLineItemWasAddedToOrder(
        LineItemWasAddedToOrder $event
    ): void {
        $product = Product::id($event->getProductId());

        $lineItem = new LineItem(
            $event->getLineItemId(),
            $product
        );

        $this->lineItems->add($lineItem);
    }

    protected function applyLineItemWasRemovedFromOrder(
        LineItemWasRemovedFromOrder $event
    ): void {
        $this->lineItems->remove($event->getLineItemId());
    }
}
