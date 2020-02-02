<?php

namespace Domain\Orders;

use App\Events\StoredEvent;

final class OrderWasCreated implements StoredEvent
{
    /**
     * @var OrderId
     */
    private $orderId;

    public function __construct(OrderId $orderId)
    {
        $this->orderId = $orderId;
    }

    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    public function toArray(): array
    {
        return [
            'orderId' => $this->orderId->toString(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            OrderId::fromString($data['orderId']),
        );
    }
}
