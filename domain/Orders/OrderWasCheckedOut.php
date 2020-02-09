<?php

namespace Domain\Orders;

use App\Events\StoredEvent;
use App\ValueObjects\EmailAddress;

final class OrderWasCheckedOut implements StoredEvent
{
    /**
     * @var OrderId
     */
    private $orderId;

    /**
     * @var EmailAddress
     */
    private $emailAddress;

    public function __construct(
        OrderId $orderId,
        EmailAddress $emailAddress
    ) {
        $this->orderId = $orderId;
        $this->emailAddress = $emailAddress;
    }

    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    public function getEmailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function toArray(): array
    {
        return [
            'orderId' => $this->orderId->toString(),
            'emailAddress' => $this->emailAddress->toString(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            OrderId::fromString($data['orderId']),
            EmailAddress::fromString($data['emailAddress']),
        );
    }
}
