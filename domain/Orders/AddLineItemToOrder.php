<?php

namespace Domain\Orders;

use Domain\Products\ProductId;

final class AddLineItemToOrder
{
    /**
     * @var LineItemId
     */
    private $lineItemId;

    /**
     * @var OrderId
     */
    private $orderId;

    /**
     * @var ProductId
     */
    private $productId;

    public function __construct(
        LineItemId $lineItemId,
        OrderId $orderId,
        ProductId $productId
    ) {
        $this->lineItemId = $lineItemId;
        $this->orderId = $orderId;
        $this->productId = $productId;
    }

    public function getLineItemId(): LineItemId
    {
        return $this->lineItemId;
    }

    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
