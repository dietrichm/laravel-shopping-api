<?php

namespace App\Http\Controllers\Orders;

use App\Http\Resources\Orders\OrderResource;
use Domain\Orders\Order;
use Domain\Orders\OrderDoesNotExist;
use Domain\Orders\OrderId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ShowController extends JsonResponse
{
    public function __invoke(
        Request $request,
        string $orderIdString
    ): JsonResponse {
        Validator::make(
            ['orderId' => $orderIdString],
            ['orderId' => ['required', 'uuid']]
        )->validate();

        $orderId = OrderId::fromString($orderIdString);

        try {
            $order = Order::id($orderId);
        } catch (OrderDoesNotExist $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return OrderResource::make($order)
            ->toResponse($request);
    }
}
