<?php

namespace Tests\Unit\App\Http\Resources\Orders;

use App\Http\Resources\Orders\OrderResource;
use Domain\Orders\Order;
use Domain\Orders\OrderId;
use Domain\Orders\OrderWasCreated;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

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
                'lineItems' => [],
                'removedLineItems' => [],
            ],
            $resource->toArray(new Request())
        );
    }
}
