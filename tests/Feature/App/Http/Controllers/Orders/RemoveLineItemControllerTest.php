<?php

namespace Tests\Feature\App\Http\Controllers\Orders;

use Domain\Orders\LineItemId;
use Domain\Orders\OrderId;
use Domain\Orders\RemoveLineItemFromOrder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

final class RemoveLineItemControllerTest extends TestCase
{
    /**
     * @test
     */
    public function itRemovesLineItemFromOrder(): void
    {
        $orderId = OrderId::generate();
        $lineItemId = LineItemId::generate();

        Bus::fake();

        $response = $this->deleteJson(
            '/api/orders/' . $orderId->toString() . '/lineitems',
            [
                'lineItemId' => $lineItemId->toString(),
            ]
        );

        Bus::assertDispatched(
            RemoveLineItemFromOrder::class,
            function (RemoveLineItemFromOrder $command) use ($orderId, $lineItemId) {
                return $command->getOrderId()->equals($orderId)
                    && $command->getLineItemId()->equals($lineItemId);
            }
        );

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @dataProvider providesInvalidLineItemIds
     */
    public function itValidatesProvidedLineItemId(
        ?string $invalidLineItemId
    ): void {
        $orderId = OrderId::generate();

        Bus::fake();

        $response = $this->deleteJson(
            '/api/orders/' . $orderId->toString() . '/lineitems',
            [
                'lineItemId' => $invalidLineItemId,
            ]
        );

        Bus::assertNotDispatched(RemoveLineItemFromOrder::class);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['lineItemId']);
    }

    public function providesInvalidLineItemIds(): array
    {
        return [
            'Missing LineItemId' => [null],
            'Invalid LineItemId' => ['foo'],
        ];
    }
}
