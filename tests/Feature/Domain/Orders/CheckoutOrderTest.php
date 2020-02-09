<?php

namespace Tests\Feature\Domain\Orders;

use App\ValueObjects\EmailAddress;
use Domain\Orders\CheckoutOrder;
use Domain\Orders\CreateOrder;
use Domain\Orders\Order;
use Domain\Orders\OrderId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CheckoutOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itChecksOutOrderWithGivenId(): void
    {
        $orderId = OrderId::generate();
        $emailAddress = EmailAddress::fromString('me@example.com');

        $this->givenOrderExists($orderId);

        (new CheckoutOrder(
            $orderId,
            $emailAddress
        ))->handle();

        $order = Order::id($orderId);

        $this->assertTrue($order->isCheckedOut());
        $this->assertEquals(
            $emailAddress,
            $order->getClientEmailAddress()
        );
    }

    private function givenOrderExists(OrderId $orderId)
    {
        CreateOrder::dispatchNow($orderId);
    }
}
