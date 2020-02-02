<?php

namespace Tests\Feature\App\Http\Controllers\Orders;

use Domain\Orders\CreateOrder;
use Domain\Orders\OrderId;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

final class CreateControllerTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesOrderAndReturnsOrderIdAsJson(): void
    {
        /** @var OrderId|null $orderId */
        $orderId = null;

        Bus::fake();

        $response = $this->postJson('/api/orders');

        Bus::assertDispatched(
            CreateOrder::class,
            function (CreateOrder $command) use (&$orderId) {
                $orderId = $command->getOrderId();

                return true;
            }
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonPath('data.id', $orderId->toString());
    }
}
