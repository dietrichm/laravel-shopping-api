<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Domain\Orders\LineItemId;
use Domain\Orders\OrderId;
use Domain\Orders\RemoveLineItemFromOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class RemoveLineItemController extends Controller
{
    public function __invoke(
        Request $request,
        string $orderIdString
    ): JsonResponse {
        $lineItemId = LineItemId::fromString($request->input('lineItemId'));
        $orderId = OrderId::fromString($orderIdString);

        RemoveLineItemFromOrder::dispatchNow(
            $lineItemId,
            $orderId
        );

        return response()->json();
    }
}
