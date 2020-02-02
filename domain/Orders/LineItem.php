<?php

namespace Domain\Orders;

use Domain\Products\ProductId;

final class LineItem
{
    /**
     * @var LineItemId
     */
    private $lineItemId;

    /**
     * @var ProductId
     */
    private $productId;

    public function __construct(LineItemId $lineItemId, ProductId $productId)
    {
        $this->lineItemId = $lineItemId;
        $this->productId = $productId;
    }

    public function getId(): LineItemId
    {
        return $this->lineItemId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
