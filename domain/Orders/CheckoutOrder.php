<?php

namespace Domain\Orders;

use App\ValueObjects\EmailAddress;
use Illuminate\Foundation\Bus\Dispatchable;

final class CheckoutOrder
{
    use Dispatchable;

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

    public function handle()
    {
        $order = Order::id($this->orderId);

        if ($order->isCheckedOut()) {
            throw OrderIsAlreadyCheckedOut::withId($this->orderId);
        }

        $order
            ->recordThat(
                new OrderWasCheckedOut(
                    $this->orderId,
                    $this->emailAddress
                )
            )
            ->persist();
    }
}
