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

    /**
     * @test
     * @dataProvider providesInvalidEmailAddresses
     */
    public function itValidatesProvidedEmailAddress(
        ?string $invalidEmailAddress
    ): void {
        $orderId = OrderId::generate();

        Bus::fake();

        $response = $this->postJson(
            '/api/orders/' . $orderId->toString() . '/checkout',
            [
                'emailAddress' => $invalidEmailAddress,
            ]
        );

        Bus::assertNotDispatched(CheckoutOrder::class);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['emailAddress']);
    }

    public function providesInvalidEmailAddresses(): array
    {
        return [
            'Missing EmailAddress' => [null],
            'Invalid EmailAddress' => ['foo'],
        ];
    }

    /**
     * @test
     */
    public function itValidatesProvidedOrderId(): void
    {
        $emailAddress = EmailAddress::fromString('me@example.org');

        Bus::fake();

        $response = $this->postJson(
            '/api/orders/foo/checkout',
            [
                'emailAddress' => $emailAddress->toString(),
            ]
        );

        Bus::assertNotDispatched(CheckoutOrder::class);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['orderId']);
    }
}
