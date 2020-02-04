<?php

namespace Domain\Orders;

use App\Events\StoredEvent;

final class LineItemWasRemovedFromOrder implements StoredEvent
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

    public function toArray(): array
    {
        return [
            'lineItemId' => $this->lineItemId->toString(),
            'orderId' => $this->orderId->toString(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            LineItemId::fromString($data['lineItemId']),
            OrderId::fromString($data['orderId']),
        );
    }
}
