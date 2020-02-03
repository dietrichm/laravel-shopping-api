<?php

namespace Tests\Feature\App\Http\Controllers\Orders;

use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\LineItemId;
use Domain\Orders\OrderId;
use Domain\Products\ProductId;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

final class AddLineItemControllerTest extends TestCase
{
    /**
     * @test
     */
    public function itAddsLineItemToOrderAndReturnsIdAsJson(): void
    {
        /** @var LineItemId|null $lineItemId */
        $lineItemId = null;
        $orderId = OrderId::generate();
        $productId = ProductId::generate();

        Bus::fake();

        $response = $this->postJson(
            '/api/orders/' . $orderId->toString() . '/lineitems',
            [
                'productId' => $productId->toString(),
            ]
        );

        Bus::assertDispatched(
            AddLineItemToOrder::class,
            function (AddLineItemToOrder $command) use (&$lineItemId, $orderId, $productId) {
                $lineItemId = $command->getLineItemId();

                return $command->getOrderId()->equals($orderId)
                    && $command->getProductId()->equals($productId);
            }
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonPath('data.id', $lineItemId->toString());
    }
}
