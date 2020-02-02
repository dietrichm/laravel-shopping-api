<?php

namespace Tests\Feature\Domain\Orders;

use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\LineItem;
use Domain\Orders\LineItemId;
use Domain\Orders\Order;
use Domain\Orders\OrderId;
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
}
