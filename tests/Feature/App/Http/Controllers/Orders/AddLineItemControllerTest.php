<?php

namespace Tests\Feature\App\Http\Controllers\Orders;

use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\LineItemId;
use Domain\Orders\OrderDoesNotExist;
use Domain\Orders\OrderId;
use Domain\Orders\OrderIsAlreadyCheckedOut;
use Domain\Products\ProductDoesNotExist;
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

    /**
     * @test
     * @dataProvider providesInvalidProductIds
     */
    public function itValidatesProvidedProductId(
        ?string $invalidProductId
    ): void {
        $orderId = OrderId::generate();

        Bus::fake();

        $response = $this->postJson(
            '/api/orders/' . $orderId->toString() . '/lineitems',
            [
                'productId' => $invalidProductId,
            ]
        );

        Bus::assertNotDispatched(AddLineItemToOrder::class);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['productId']);
    }

    public function providesInvalidProductIds(): array
    {
        return [
            'Missing ProductId' => [null],
            'Invalid ProductId' => ['foo'],
        ];
    }

    /**
     * @test
     */
    public function itValidatesProvidedOrderId(): void
    {
        $productId = ProductId::generate();

        Bus::fake();

        $response = $this->postJson(
            '/api/orders/foo/lineitems',
            [
                'productId' => $productId->toString(),
            ]
        );

        Bus::assertNotDispatched(AddLineItemToOrder::class);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['orderId']);
    }

    /**
     * @test
     */
    public function itFailsWhenOrderDoesNotExist(): void
    {
        $orderId = OrderId::generate();
        $productId = ProductId::generate();
        $exception = OrderDoesNotExist::withId($orderId);

        Bus::shouldReceive('dispatchNow')
            ->andThrow($exception);

        $response = $this->postJson(
            '/api/orders/' . $orderId->toString() . '/lineitems',
            [
                'productId' => $productId->toString(),
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', $exception->getMessage());
    }

    /**
     * @test
     */
    public function itFailsWhenProductDoesNotExist(): void
    {
        $orderId = OrderId::generate();
        $productId = ProductId::generate();
        $exception = ProductDoesNotExist::withId($productId);

        Bus::shouldReceive('dispatchNow')
            ->andThrow($exception);

        $response = $this->postJson(
            '/api/orders/' . $orderId->toString() . '/lineitems',
            [
                'productId' => $productId->toString(),
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
        $productId = ProductId::generate();
        $exception = OrderIsAlreadyCheckedOut::withId($orderId);

        Bus::shouldReceive('dispatchNow')
            ->andThrow($exception);

        $response = $this->postJson(
            '/api/orders/' . $orderId->toString() . '/lineitems',
            [
                'productId' => $productId->toString(),
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', $exception->getMessage());
    }
}
