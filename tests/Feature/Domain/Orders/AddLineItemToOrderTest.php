<?php

namespace Tests\Feature\Domain\Orders;

use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\LineItem;
use Domain\Orders\LineItemId;
use Domain\Orders\Order;
use Domain\Orders\OrderDoesNotExist;
use Domain\Orders\OrderId;
use Domain\Orders\OrderWasCreated;
use Domain\Products\ProductId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AddLineItemToOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itAddsLineItemToOrder(): void
    {
        $lineItemId = LineItemId::generate();
        $orderId = OrderId::generate();
        $productId = ProductId::generate();

        $this->givenOrderExists($orderId);

        (new AddLineItemToOrder(
            $lineItemId,
            $orderId,
            $productId
        ))->handle();

        $order = Order::findOrCreate($orderId);

        $expectedLineItem = new LineItem(
            $lineItemId,
            $productId
        );

        $this->assertEquals(
            [$expectedLineItem],
            $order->getLineItems()
        );
    }

    /**
     * @test
     */
    public function itDoesNotAddLineItemToNewOrder(): void
    {
        $lineItemId = LineItemId::generate();
        $orderId = OrderId::generate();
        $productId = ProductId::generate();

        $this->expectException(OrderDoesNotExist::class);

        (new AddLineItemToOrder(
            $lineItemId,
            $orderId,
            $productId
        ))->handle();
    }

    private function givenOrderExists(OrderId $orderId)
    {
        Order::findOrCreate($orderId)
            ->recordThat(
                new OrderWasCreated($orderId)
            )
            ->persist();
    }
}
