<?php

namespace Tests\Feature\Domain\Orders;

use App\ValueObjects\EmailAddress;
use Domain\Orders\CheckoutOrder;
use Domain\Orders\CreateOrder;
use Domain\Orders\Order;
use Domain\Orders\OrderId;
use Domain\Orders\OrderIsAlreadyCheckedOut;
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

    /**
     * @test
     */
    public function itDoesNotCheckoutOrderThatIsAlreadyCheckedOut(): void
    {
        $orderId = OrderId::generate();
        $emailAddress = EmailAddress::fromString('me@example.com');

        $this->givenOrderExists($orderId);
        $this->givenOrderIsCheckedOut($orderId);

        $this->expectException(OrderIsAlreadyCheckedOut::class);

        (new CheckoutOrder(
            $orderId,
            $emailAddress
        ))->handle();
    }

    private function givenOrderExists(OrderId $orderId)
    {
        CreateOrder::dispatchNow($orderId);
    }

    private function givenOrderIsCheckedOut(OrderId $orderId): void
    {
        CheckoutOrder::dispatchNow(
            $orderId,
            EmailAddress::fromString('me@example.org')
        );
    }
}
