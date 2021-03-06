<?php

namespace Tests\Feature\App\Http\Controllers\Orders;

use App\ValueObjects\EmailAddress;
use Domain\Orders\CheckoutOrder;
use Domain\Orders\OrderDoesNotExist;
use Domain\Orders\OrderId;
use Domain\Orders\OrderIsAlreadyCheckedOut;
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

        $response->assertStatus(Response::HTTP_NO_CONTENT);
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

    /**
     * @test
     */
    public function itFailsWhenOrderDoesNotExist(): void
    {
        $orderId = OrderId::generate();
        $emailAddress = EmailAddress::fromString('me@example.org');
        $exception = OrderDoesNotExist::withId($orderId);

        Bus::shouldReceive('dispatchNow')
            ->andThrow($exception);

        $response = $this->postJson(
            '/api/orders/' . $orderId->toString() . '/checkout',
            [
                'emailAddress' => $emailAddress->toString(),
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', $exception->getMessage());
    }

    /**
     * @test
     */
    public function itFailsWhenOrderIsCheckedOut(): void
    {
        $orderId = OrderId::generate();
        $emailAddress = EmailAddress::fromString('me@example.org');
        $exception = OrderIsAlreadyCheckedOut::withId($orderId);

        Bus::shouldReceive('dispatchNow')
            ->andThrow($exception);

        $response = $this->postJson(
            '/api/orders/' . $orderId->toString() . '/checkout',
            [
                'emailAddress' => $emailAddress->toString(),
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', $exception->getMessage());
    }
}
