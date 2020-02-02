<?php

namespace App\Http\Resources\Orders;

use Domain\Orders\OrderId;
use Illuminate\Http\Resources\Json\JsonResource;

final class OrderIdResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        /** @var OrderId $orderId */
        $orderId = $this->resource;

        return [
            'id' => $orderId->toString(),
        ];
    }
}
