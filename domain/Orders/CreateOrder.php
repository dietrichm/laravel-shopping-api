<?php

namespace Domain\Orders;

use Illuminate\Foundation\Bus\Dispatchable;

final class CreateOrder
{
    use Dispatchable;

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

    public function handle()
    {
        Order::findOrCreate($this->orderId)
            ->recordThat(
                new OrderWasCreated($this->orderId)
            )
            ->persist();
    }
}
