<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrderIdResource;
use Domain\Orders\CreateOrder;
use Domain\Orders\OrderId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CreateController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $orderId = OrderId::generate();

        CreateOrder::dispatchNow($orderId);

        return OrderIdResource::make($orderId)
            ->toResponse($request);
    }
}
