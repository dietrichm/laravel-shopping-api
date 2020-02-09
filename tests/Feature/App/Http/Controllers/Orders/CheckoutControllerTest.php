<?php

namespace Tests\Feature\App\Http\Controllers\Orders;

use App\ValueObjects\EmailAddress;
use Domain\Orders\CheckoutOrder;
use Domain\Orders\OrderId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

final class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itChecksOutSpecifiedOrder(): void
    {
        $orderId = OrderId::generate();
        $emailAddress = EmailAddress::fromString('me@example.org');

        Bus::fake();

        $response = $this->postJson(
            '/api/orders/' . $orderId->toString() . '/checkout',
            [
                'emailAddress' => $emailAddress->toString(),
            ]
        );

        Bus::assertDispatched(
            CheckoutOrder::class,
            function (CheckoutOrder $command) use ($orderId, $emailAddress) {
                return $command->getOrderId()->equals($orderId)
                    && $command->getEmailAddress()->equals($emailAddress);
            }
        );

        $response->assertStatus(Response::HTTP_OK);
    }
}
