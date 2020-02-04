<?php

namespace Tests\Feature\Domain\Orders;

use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\CreateOrder;
use Domain\Orders\LineItemId;
use Domain\Orders\Order;
use Domain\Orders\OrderId;
use Domain\Orders\RemoveLineItemFromOrder;
use Domain\Products\Product;
use Domain\Products\ProductId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RemoveLineItemFromOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itRemovesLineItemFromOrder(): void
    {
        /** @var Product $product */
        $product = factory(Product::class)->create();

        $lineItemId = LineItemId::generate();
        $orderId = OrderId::generate();
        $productId = $product->getId();

        $this->givenOrderExists($orderId);
        $this->givenOrderHasLineItem(
            $lineItemId,
            $orderId,
            $productId
        );

        (new RemoveLineItemFromOrder(
            $lineItemId,
            $orderId
        ))->handle();

        $order = Order::id($orderId);

        $this->assertEmpty($order->getLineItems());
    }

    private function givenOrderExists(OrderId $orderId): void
    {
        CreateOrder::dispatchNow($orderId);
    }

    private function givenOrderHasLineItem(
        LineItemId $lineItemId,
        OrderId $orderId,
        ProductId $productId
    ): void {
        AddLineItemToOrder::dispatchNow(
            $lineItemId,
            $orderId,
            $productId
        );
    }
}
