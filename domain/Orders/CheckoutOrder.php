<?php

namespace Domain\Orders;

use App\ValueObjects\EmailAddress;

final class CheckoutOrder
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
}
