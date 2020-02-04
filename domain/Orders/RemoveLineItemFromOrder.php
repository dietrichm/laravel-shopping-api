<?php

namespace Domain\Orders;

final class RemoveLineItemFromOrder
{
    /**
     * @var LineItemId
     */
    private $lineItemId;

    /**
     * @var OrderId
     */
    private $orderId;

    public function __construct(
        LineItemId $lineItemId,
        OrderId $orderId
    ) {
        $this->lineItemId = $lineItemId;
        $this->orderId = $orderId;
    }

    public function getLineItemId(): LineItemId
    {
        return $this->lineItemId;
    }

    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    public function handle()
    {
        $order = Order::id($this->orderId);

        if (!$order->hasLineItem($this->lineItemId)) {
            throw LineItemDoesNotExist::withId($this->lineItemId);
        }

        $order
            ->recordThat(new LineItemWasRemovedFromOrder(
                $this->lineItemId,
                $this->orderId
            ))
            ->persist();
    }
}
