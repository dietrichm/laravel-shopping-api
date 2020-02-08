<?php

namespace App\Http\Controllers\Orders;

use App\Http\Resources\Orders\OrderResource;
use Domain\Orders\Order;
use Domain\Orders\OrderId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ShowController extends JsonResponse
{
    public function __invoke(
        Request $request,
        string $orderIdString
    ): JsonResponse {
        $orderId = OrderId::fromString($orderIdString);
        $order = Order::id($orderId);

        return OrderResource::make($order)
            ->toResponse($request);
    }
}
