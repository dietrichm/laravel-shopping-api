<?php

namespace Tests\Unit\App\Http\Resources\Orders;

use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Products\ProductResource;
use Domain\Orders\LineItem;
use Domain\Orders\LineItemId;
use Domain\Orders\Order;
use Domain\Orders\OrderId;
use Domain\Orders\OrderWasCreated;
use Domain\Products\Product;
use Illuminate\Http\Request;
use Tests\TestCase;

final class OrderResourceTest extends TestCase
{
    /**
     * @test
     */
    public function itTransformsEmptyOrderIntoArray(): void
    {
        $orderId = OrderId::generate();

        $order = (new Order())
            ->recordThat(new OrderWasCreated($orderId));

        $resource = new OrderResource($order);

        $this->assertEquals(
            [
                'id' => $orderId->toString(),
                'totalPrice' => 0,
                'isCheckedOut' => false,
                'lineItems' => [],
                'removedLineItems' => [],
            ],
            $resource->toArray(new Request())
        );
    }

    /**
     * @test
     */
    public function itIncludesLineItemsInArray(): void
    {
        $orderId = OrderId::generate();

        /** @var Order $order */
        $order = (new Order())
            ->recordThat(new OrderWasCreated($orderId));

        $lineItemOne = $this->givenThereIsALineItem([
            'price' => 85.11,
        ]);
        $order->getLineItems()->add($lineItemOne);

        $lineItemTwo = $this->givenThereIsALineItem([
            'price' => 19.2,
        ]);
        $order->getLineItems()->add($lineItemTwo);

        $resource = new OrderResource($order);
        $request = new Request();

        $this->assertEquals(
            [
                'id' => $orderId->toString(),
                'totalPrice' => 104.31,
                'isCheckedOut' => false,
                'lineItems' => [
                    [
                        'id' => $lineItemOne->getId()->toString(),
                        'product' => (new ProductResource($lineItemOne->getProduct()))
                            ->toArray($request),
                    ],
                    [
                        'id' => $lineItemTwo->getId()->toString(),
                        'product' => (new ProductResource($lineItemTwo->getProduct()))
                            ->toArray($request),
                    ],
                ],
                'removedLineItems' => [],
            ],
            $resource->toArray($request)
        );
    }

    /**
     * @test
     */
    public function itIncludesRemovedLineItemsInArray(): void
    {
        $orderId = OrderId::generate();

        /** @var Order $order */
        $order = (new Order())
            ->recordThat(new OrderWasCreated($orderId));

        $lineItemOne = $this->givenThereIsALineItem();
        $order->getRemovedLineItems()->add($lineItemOne);

        $lineItemTwo = $this->givenThereIsALineItem();
        $order->getRemovedLineItems()->add($lineItemTwo);

        $resource = new OrderResource($order);
        $request = new Request();

        $this->assertEquals(
            [
                'id' => $orderId->toString(),
                'totalPrice' => 0,
                'isCheckedOut' => false,
                'lineItems' => [],
                'removedLineItems' => [
                    [
                        'id' => $lineItemOne->getId()->toString(),
                        'product' => (new ProductResource($lineItemOne->getProduct()))
                            ->toArray($request),
                    ],
                    [
                        'id' => $lineItemTwo->getId()->toString(),
                        'product' => (new ProductResource($lineItemTwo->getProduct()))
                            ->toArray($request),
                    ],
                ],
            ],
            $resource->toArray($request)
        );
    }

    private function givenThereIsALineItem(
        array $productProperties = []
    ): LineItem {
        return new LineItem(
            LineItemId::generate(),
            factory(Product::class)->make($productProperties)
        );
    }
}
