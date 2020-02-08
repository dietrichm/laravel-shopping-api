<?php

namespace Tests\Feature\App\Http\Resources\Orders;

use App\Http\Resources\Orders\OrderResource;
use Domain\Orders\Order;
use Domain\Orders\OrderId;
use Domain\Orders\OrderWasCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

final class OrderResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itTransformsEmptyOrderIntoArray(): void
    {
        $orderId = OrderId::generate();
        $order = null;

        Order::fake([
            new OrderWasCreated($orderId),
        ])->when(function (Order $fakeOrder) use (&$order) {
            $order = $fakeOrder;
        });

        $resource = new OrderResource($order);

        $this->assertEquals(
            [
                'id' => $orderId->toString(),
                'totalPrice' => 0,
                'lineItems' => [],
                'removedLineItems' => [],
            ],
            $resource->toArray(new Request())
        );
    }
}
