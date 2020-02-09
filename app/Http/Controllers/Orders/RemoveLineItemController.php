<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Domain\Orders\LineItemDoesNotExist;
use Domain\Orders\LineItemId;
use Domain\Orders\OrderDoesNotExist;
use Domain\Orders\OrderId;
use Domain\Orders\OrderIsAlreadyCheckedOut;
use Domain\Orders\RemoveLineItemFromOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

        try {
            RemoveLineItemFromOrder::dispatchNow(
                $lineItemId,
                $orderId
            );
        } catch (OrderDoesNotExist
            | OrderIsAlreadyCheckedOut
            | LineItemDoesNotExist $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return response()->json();
    }
}
