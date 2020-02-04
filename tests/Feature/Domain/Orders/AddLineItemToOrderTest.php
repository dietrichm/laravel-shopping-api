<?php

namespace Tests\Feature\Domain\Orders;

use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\CreateOrder;
use Domain\Orders\LineItem;
use Domain\Orders\LineItemId;
use Domain\Orders\Order;
use Domain\Orders\OrderDoesNotExist;
use Domain\Orders\OrderId;
use Domain\Products\Product;
use Domain\Products\ProductDoesNotExist;
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
        /** @var Product $product */
        $product = factory(Product::class)->create();

        $lineItemId = LineItemId::generate();
        $orderId = OrderId::generate();
        $productId = $product->getId();

        $this->givenOrderExists($orderId);

        (new AddLineItemToOrder(
            $lineItemId,
            $orderId,
            $productId
        ))->handle();

        $order = Order::findOrCreate($orderId);

        $expectedLineItem = new LineItem(
            $lineItemId,
            $product->fresh()
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

    /**
     * @test
     */
    public function itDoesNotAddLineItemWithNonExistingProduct(): void
    {
        $lineItemId = LineItemId::generate();
        $orderId = OrderId::generate();
        $productId = ProductId::generate();

        $this->givenOrderExists($orderId);

        $this->expectException(ProductDoesNotExist::class);

        (new AddLineItemToOrder(
            $lineItemId,
            $orderId,
            $productId
        ))->handle();
    }

    private function givenOrderExists(OrderId $orderId)
    {
        CreateOrder::dispatchNow($orderId);
    }
}
