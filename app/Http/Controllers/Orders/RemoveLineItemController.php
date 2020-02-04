<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Domain\Orders\LineItemId;
use Domain\Orders\OrderId;
use Domain\Orders\RemoveLineItemFromOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class RemoveLineItemController extends Controller
{
    public function __invoke(
        Request $request,
        string $orderIdString
    ): JsonResponse {
        Validator::make(
            array_merge(
                ['orderId' => $orderIdString],
                $request->all()
            ),
            [
                'orderId' => ['required', 'uuid'],
                'lineItemId' => ['required', 'uuid'],
            ]
        )->validate();

        $lineItemId = LineItemId::fromString($request->input('lineItemId'));
        $orderId = OrderId::fromString($orderIdString);

        RemoveLineItemFromOrder::dispatchNow(
            $lineItemId,
            $orderId
        );

        return response()->json();
    }
}
