<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\LineItemIdResource;
use Domain\Orders\AddLineItemToOrder;
use Domain\Orders\LineItemId;
use Domain\Orders\OrderDoesNotExist;
use Domain\Orders\OrderId;
use Domain\Orders\OrderIsAlreadyCheckedOut;
use Domain\Products\ProductDoesNotExist;
use Domain\Products\ProductId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class AddLineItemController extends Controller
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
                'productId' => ['required', 'uuid'],
            ]
        )->validate();

        $lineItemId = LineItemId::generate();
        $orderId = OrderId::fromString($orderIdString);
        $productId = ProductId::fromString($request->input('productId'));

        try {
            AddLineItemToOrder::dispatchNow(
                $lineItemId,
                $orderId,
                $productId
            );
        } catch (OrderDoesNotExist
            | OrderIsAlreadyCheckedOut
            | ProductDoesNotExist $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return LineItemIdResource::make($lineItemId)
            ->toResponse($request)
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
