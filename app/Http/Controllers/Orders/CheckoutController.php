<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\ValueObjects\EmailAddress;
use Domain\Orders\CheckoutOrder;
use Domain\Orders\OrderDoesNotExist;
use Domain\Orders\OrderId;
use Domain\Orders\OrderIsAlreadyCheckedOut;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CheckoutController extends Controller
{
    public function __invoke(
        Request $request,
        string $orderIdString
    ): Response {
        Validator::make(
            array_merge(
                ['orderId' => $orderIdString],
                $request->all()
            ),
            [
                'orderId' => ['required', 'uuid'],
                'emailAddress' => ['required', 'email'],
            ]
        )->validate();

        $orderId = OrderId::fromString($orderIdString);
        $emailAddress = EmailAddress::fromString($request->input('emailAddress'));

        try {
            CheckoutOrder::dispatchNow(
                $orderId,
                $emailAddress
            );
        } catch (OrderDoesNotExist | OrderIsAlreadyCheckedOut $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return response()->noContent();
    }
}
