<?php

namespace Domain\Orders;

use App\Events\StoredEvent;
use Domain\Products\ProductId;

final class LineItemWasAddedToOrder implements StoredEvent
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

    public function toArray(): array
    {
        return [
            'lineItemId' => $this->lineItemId->toString(),
            'orderId' => $this->orderId->toString(),
            'productId' => $this->productId->toString(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            LineItemId::fromString($data['lineItemId']),
            OrderId::fromString($data['orderId']),
            ProductId::fromString($data['productId']),
        );
    }
}
