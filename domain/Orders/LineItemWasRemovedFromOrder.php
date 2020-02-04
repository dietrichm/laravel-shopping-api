<?php

namespace Domain\Orders;

final class LineItemWasRemovedFromOrder
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
}
