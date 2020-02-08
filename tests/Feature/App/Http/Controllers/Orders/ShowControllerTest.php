<?php

namespace Tests\Feature\App\Http\Controllers\Orders;

use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\CreateOrder;
use Domain\Orders\LineItemId;
use Domain\Orders\OrderId;
use Domain\Orders\RemoveLineItemFromOrder;
use Domain\Products\Product;
use Domain\Products\ProductId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ShowControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itReturnsOrderAsJson(): void
    {
        $orderId = OrderId::generate();
        $lineItemIdOne = LineItemId::generate();
        $lineItemIdTwo = LineItemId::generate();

        /** @var Product $product */
        $productOne = factory(Product::class)->create([
            'price' => 128.2
        ]);
        $productIdOne = $productOne->getId();

        /** @var Product $product */
        $productTwo = factory(Product::class)->create([
            'price' => 96
        ]);
        $productIdTwo = $productTwo->getId();

        $this->givenOrderExists($orderId);

        $this->givenOrderHasLineItem(
            $lineItemIdOne,
            $orderId,
            $productIdOne
        );

        $this->givenOrderHasRemovedLineItem(
            $lineItemIdTwo,
            $orderId,
            $productIdTwo
        );

        $response = $this->getJson(
            '/api/orders/' . $orderId->toString()
        );

        $response->assertJsonPath('data', [
            'id' => $orderId->toString(),
            'totalPrice' => 128.2,
            'lineItems' => [
                [
                    'id' => $lineItemIdOne->toString(),
                    'product' => [
                        'id' => $productIdOne->toString(),
                        'name' => $productOne->getName()->toString(),
                        'price' => 128.2,
                    ],
                ],
            ],
            'removedLineItems' => [
                [
                    'id' => $lineItemIdTwo->toString(),
                    'product' => [
                        'id' => $productIdTwo->toString(),
                        'name' => $productTwo->getName()->toString(),
                        'price' => 96,
                    ],
                ],
            ],
        ]);
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

    private function givenOrderHasRemovedLineItem(
        LineItemId $lineItemId,
        OrderId $orderId,
        ProductId $productId
    ): void {
        AddLineItemToOrder::dispatchNow(
            $lineItemId,
            $orderId,
            $productId
        );
        RemoveLineItemFromOrder::dispatchNow(
            $lineItemId,
            $orderId
        );
    }
}
