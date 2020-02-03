<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\LineItemIdResource;
use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\LineItemId;
use Domain\Orders\OrderId;
use Domain\Products\ProductId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AddLineItemController extends Controller
{
    public function __invoke(
        Request $request,
        string $orderIdString
    ): JsonResponse {
        $lineItemId = LineItemId::generate();
        $orderId = OrderId::fromString($orderIdString);
        $productId = ProductId::fromString($request->input('productId'));

        AddLineItemToOrder::dispatchNow(
            $lineItemId,
            $orderId,
            $productId
        );

        return LineItemIdResource::make($lineItemId)
            ->toResponse($request)
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
