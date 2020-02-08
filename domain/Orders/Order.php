<?php

namespace Domain\Orders;

use App\ValueObjects\Money;
use Domain\Products\Product;
use Spatie\EventSourcing\AggregateRoot;

final class Order extends AggregateRoot
{
    /**
     * @var OrderId|null
     */
    private $orderId;

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
        return parent::retrieve($orderId->toString());
    }

    public static function id(OrderId $orderId): self
    {
        $order = self::findOrCreate($orderId);

        if ($order->isNew()) {
            throw OrderDoesNotExist::withId($orderId);
        }

        return $order;
    }

    public function getId(): ?OrderId
    {
        return $this->orderId;
    }

    public function isNew(): bool
    {
        return $this->orderId === null;
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

    public function getTotalPrice(): Money
    {
        return $this->lineItems->getTotalPrice();
    }

    /**
     * @return LineItem[]
     */
    public function getRemovedLineItems(): array
    {
        return $this->removedLineItems->all();
    }

    protected function applyOrderWasCreated(
        OrderWasCreated $event
    ): void {
        $this->orderId = $event->getOrderId();
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
        $lineItem = $this->lineItems->remove($event->getLineItemId());
        $this->removedLineItems->add($lineItem);
    }
}
