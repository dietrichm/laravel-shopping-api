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
use Illuminate\Support\Facades\Validator;

final class AddLineItemController extends Controller
{
    public function __invoke(
        Request $request,
        string $orderIdString
    ): JsonResponse {
        $validator = Validator::make(
            array_merge(
                ['orderId' => $orderIdString],
                $request->all()
            ),
            [
                'orderId' => ['required', 'uuid'],
                'productId' => ['required', 'uuid'],
            ]
        );

        if ($validator->fails()) {
            return response()
                ->json($validator->errors())
                ->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
        }

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
