<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\ValueObjects\EmailAddress;
use Domain\Orders\CheckoutOrder;
use Domain\Orders\OrderId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class CheckoutController extends Controller
{
    public function __invoke(
        Request $request,
        string $orderIdString
    ): JsonResponse {
        Validator::make(
            $request->all(),
            [
                'emailAddress' => ['required', 'email'],
            ]
        )->validate();

        $orderId = OrderId::fromString($orderIdString);
        $emailAddress = EmailAddress::fromString($request->input('emailAddress'));

        CheckoutOrder::dispatchNow(
            $orderId,
            $emailAddress
        );

        return response()->json();
    }
}
