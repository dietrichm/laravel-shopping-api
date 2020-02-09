<?php

namespace Domain\Orders;

use Domain\Products\Product;
use Domain\Products\ProductId;
use Illuminate\Foundation\Bus\Dispatchable;

final class AddLineItemToOrder
{
    use Dispatchable;

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

    public function handle()
    {
        $order = Order::id($this->orderId);

        if ($order->isCheckedOut()) {
            throw OrderIsAlreadyCheckedOut::withId($this->orderId);
        }

        $product = Product::id($this->productId);

        $order
            ->recordThat(new LineItemWasAddedToOrder(
                $this->lineItemId,
                $order->getId(),
                $product->getId()
            ))
            ->persist();
    }
}
