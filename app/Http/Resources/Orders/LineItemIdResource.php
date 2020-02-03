<?php

namespace App\Http\Resources\Orders;

use Domain\Orders\LineItemId;
use Illuminate\Http\Resources\Json\JsonResource;

final class LineItemIdResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        /** @var LineItemId $lineItemId */
        $lineItemId = $this->resource;

        return [
            'id' => $lineItemId->toString(),
        ];
    }
}
