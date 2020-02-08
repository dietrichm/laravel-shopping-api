<?php

namespace App\Http\Resources\Orders;

use Domain\Orders\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class OrderResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        /** @var Order $order */
        $order = $this->resource;

        return [
            'id' => $order->getId()->toString(),
            'totalPrice' => $order->getTotalPrice()->getValue(),
            'lineItems' => [],
            'removedLineItems' => [],
        ];
    }
}
