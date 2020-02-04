<?php

namespace Domain\Orders;

use Domain\Products\Product;

final class LineItem
{
    /**
     * @var LineItemId
     */
    private $lineItemId;

    /**
     * @var Product
     */
    private $product;

    public function __construct(LineItemId $lineItemId, Product $product)
    {
        $this->lineItemId = $lineItemId;
        $this->product = $product;
    }

    public function getId(): LineItemId
    {
        return $this->lineItemId;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
