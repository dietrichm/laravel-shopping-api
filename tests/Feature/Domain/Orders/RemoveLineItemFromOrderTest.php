<?php

namespace Tests\Feature\Domain\Orders;

use App\ValueObjects\EmailAddress;
use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\CheckoutOrder;
use Domain\Orders\CreateOrder;
use Domain\Orders\LineItem;
use Domain\Orders\LineItemDoesNotExist;
use Domain\Orders\LineItemId;
use Domain\Orders\Order;
use Domain\Orders\OrderDoesNotExist;
use Domain\Orders\OrderId;
use Domain\Orders\OrderIsAlreadyCheckedOut;
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
    public function itRemovesSpecifiedLineItemFromOrder(): void
    {
        /** @var Product $product */
        $product = factory(Product::class)->create();

        $lineItemIdOne = LineItemId::generate();
        $lineItemIdTwo = LineItemId::generate();
        $orderId = OrderId::generate();
        $productId = $product->getId();

        $this->givenOrderExists($orderId);

        $this->givenOrderHasLineItem(
            $lineItemIdOne,
            $orderId,
            $productId
        );
        $this->givenOrderHasLineItem(
            $lineItemIdTwo,
            $orderId,
            $productId
        );

        (new RemoveLineItemFromOrder(
            $lineItemIdOne,
            $orderId
        ))->handle();

        $order = Order::id($orderId);

        $expectedLineItem = new LineItem(
            $lineItemIdTwo,
            $product->fresh()
        );

        $this->assertEquals(
            [$expectedLineItem],
            $order->getLineItems()->all()
        );
    }

    /**
     * @test
     */
    public function itStoresRemovedLineItemInRemovedCollection(): void
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

        $expectedLineItem = new LineItem(
            $lineItemId,
            $product->fresh()
        );

        $this->assertEquals(
            [$expectedLineItem],
            $order->getRemovedLineItems()->all()
        );
    }

    /**
     * @test
     */
    public function itDoesNotRemoveLineItemFromNewOrder(): void
    {
        $lineItemId = LineItemId::generate();
        $orderId = OrderId::generate();

        $this->expectException(OrderDoesNotExist::class);

        (new RemoveLineItemFromOrder(
            $lineItemId,
            $orderId
        ))->handle();
    }

    /**
     * @test
     */
    public function itFailsWhenLineItemDoesNotExist(): void
    {
        $lineItemId = LineItemId::generate();
        $orderId = OrderId::generate();

        $this->givenOrderExists($orderId);

        $this->expectException(LineItemDoesNotExist::class);

        (new RemoveLineItemFromOrder(
            $lineItemId,
            $orderId
        ))->handle();
    }

    /**
     * @test
     */
    public function itDoesNotRemoveLineItemFromCheckedOutOrder(): void
    {
        $lineItemId = LineItemId::generate();
        $orderId = OrderId::generate();

        $this->givenOrderExists($orderId);
        $this->givenOrderIsCheckedOut($orderId);

        $this->expectException(OrderIsAlreadyCheckedOut::class);

        (new RemoveLineItemFromOrder(
            $lineItemId,
            $orderId
        ))->handle();
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

    private function givenOrderIsCheckedOut(OrderId $orderId): void
    {
        CheckoutOrder::dispatchNow(
            $orderId,
            EmailAddress::fromString('me@example.org')
        );
    }
}
