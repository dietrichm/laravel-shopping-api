<?php

namespace Domain\Orders;

use App\ValueObjects\Money;
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

    public function getTotalPrice(): Money
    {
        return $this->getProduct()->getPrice();
    }
}
