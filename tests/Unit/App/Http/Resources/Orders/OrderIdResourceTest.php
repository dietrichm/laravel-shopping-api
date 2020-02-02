<?php

namespace Tests\Unit\App\Http\Resources\Orders;

use App\Http\Resources\Orders\OrderIdResource;
use Domain\Orders\OrderId;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

final class OrderIdResourceTest extends TestCase
{
    /**
     * @test
     */
    public function itTransformsOrderIdIntoArray(): void
    {
        $orderId = OrderId::generate();

        $resource = new OrderIdResource($orderId);

        $this->assertEquals(
            [
                'id' => $orderId->toString(),
            ],
            $resource->toArray(new Request())
        );
    }
}
